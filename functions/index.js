/**
 * Import function triggers from their respective submodules:
 *
 * const {onCall} = require("firebase-functions/v2/https");
 * const {onDocumentWritten} = require("firebase-functions/v2/firestore");
 *
 * See a full list of supported triggers at https://firebase.google.com/docs/functions
 */

const {onRequest} = require("firebase-functions/v2/https");
const logger = require("firebase-functions/logger");

// Create and deploy your first functions
// https://firebase.google.com/docs/functions/get-started

// exports.helloWorld = onRequest((request, response) => {
//   logger.info("Hello logs!", {structuredData: true});
//   response.send("Hello from Firebase!");
// });

const functions = require('firebase-functions');
const admin = require('firebase-admin');
admin.initializeApp();

const db = admin.firestore();

exports.updateReactionsCount = functions.firestore
  .document('post_likes/{likeId}')
  .onCreate((snapshot, context) => {
    const postId = snapshot.data().postid;
    return updateReactionsCount(postId);
  });

exports.deleteReaction = functions.firestore
  .document('post_likes/{likeId}')
  .onDelete((snapshot, context) => {
    const postId = snapshot.data().postid;
    return updateReactionsCount(postId);
  });

exports.updateCommentsCount = functions.firestore
  .document('post_comment/{commentId}')
  .onCreate((snapshot, context) => {
    const postId = snapshot.data().postid;
    return updateCommentsCount(postId);
  });

exports.deleteComment = functions.firestore
  .document('post_comment/{commentId}')
  .onDelete((snapshot, context) => {
    const postId = snapshot.data().postid;
    return updateCommentsCount(postId);
  });

const updateReactionsCount = (postId) => {
  const likesRef = db.collection('post_likes').where('postid', '==', postId);
  
  return likesRef.get()
    .then((snapshot) => {
      const reactionsCount = snapshot.size;
      
      return db.collection('posts').doc(postId)
        .update({ reactions: reactionsCount });
    })
    .catch((error) => {
      console.error('Error updating reactions count:', error);
    });
};

const updateCommentsCount = (postId) => {
  const commentsRef = db.collection('post_comment').where('postid', '==', postId);

  return commentsRef.get()
    .then((snapshot) => {
      const commentsCount = snapshot.size;
      
      return db.collection('posts').doc(postId)
        .update({ comments: commentsCount });
    })
    .catch((error) => {
      console.error('Error updating comments count:', error);
    });
};