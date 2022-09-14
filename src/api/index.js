import register from './register.js';
import getItems from './getItems.js';
import setViewed from './setViewed.js';
import getComments from './getComments.js';
import addComment from './addComment.js';
import addFavorite from './addFavorite.js';
import deleteFavorite from './deleteFavorite.js';
import sendReport from './sendReport.js';
import cancelReport from './cancelReport.js';
import getAudit from './getAudit.js';
import setAudit from './setAudit.js';

const API = {
  register: register,
	getItems: getItems,
  setViewed: setViewed,
  getComments: getComments,
  addComment: addComment,
  getAudit: getAudit,
  addFavorite: addFavorite,
  deleteFavorite: deleteFavorite,
  sendReport: sendReport,
  cancelReport: cancelReport,
  setAudit: setAudit,
};

export default API;