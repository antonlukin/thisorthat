import register from './register.js';
import getItems from './getItems.js';
import setViewed from './setViewed.js';
import getComments from './getComments.js';
import addComment from './addComment.js';
import getRecent from './getRecent.js';
import addFavorite from './addFavorite.js';
import deleteFavorite from './deleteFavorite.js';
import sendReport from './sendReport.js';
import cancelReport from './cancelReport.js';

const API = {
  register: register,
	getItems: getItems,
  setViewed: setViewed,
  getComments: getComments,
  addComment: addComment,
  getRecent: getRecent,
  addFavorite: addFavorite,
  deleteFavorite: deleteFavorite,
  sendReport: sendReport,
  cancelReport: cancelReport,
};

export default API;