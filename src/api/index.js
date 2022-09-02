import register from './register.js';
import getItems from './getItems.js';
import setViewed from './setViewed.js';
import getComments from './getComments.js';
import addComment from './addComment.js';
import getRecent from './getRecent.js';

const API = {
  register: register,
	getItems: getItems,
  setViewed: setViewed,
  getComments: getComments,
  addComment: addComment,
  getRecent: getRecent,
};

export default API;