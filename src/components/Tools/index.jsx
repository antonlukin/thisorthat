import { useState, useContext, useEffect } from 'react';

import { ReactComponent as VoteIcon } from '../../images/vote.svg';
import { ReactComponent as VotedIcon } from '../../images/voted.svg';
import { ReactComponent as DiscussIcon } from '../../images/discuss.svg';

import AuthContext from '../../context';
import API from '../../api';

import './styles.scss';

const Tools = function({current, toggleComments}) {
  const [voted, setVoted] = useState({like: false, dislike: false});
  const { token } = useContext(AuthContext);

  useEffect(() => {
    setVoted({ like: false, dislike: false });
  }, [current])

  async function addFavorite() {
    setVoted({...voted, like: true});

    try {
      await API.addFavorite(token, current.item_id);
    } catch (error) {
      setVoted({...voted, like: false});
    }
  }

  async function deleteFavorite() {
    setVoted({...voted, like: false});

    try {
      await API.deleteFavorite(token, current.item_id);
    } catch (error) {
      setVoted({...voted, like: true});
    }
  }

  async function sendReport() {
    setVoted({...voted, dislike: true});

    try {
      await API.sendReport(token, current.item_id);
    } catch (error) {
      setVoted({...voted, dislike: false});
    }
  }

  async function cancelReport() {
    setVoted({...voted, dislike: false});

    try {
      await API.cancelReport(token, current.item_id);
    } catch (error) {
      setVoted({...voted, dislike: true});
    }
  }

  function handleLike() {
    if (voted.dislike) {
      return;
    }

    if (voted.like) {
      return deleteFavorite();
    }

    return addFavorite();
  }

  function handleDislike() {
    if (voted.like) {
      return;
    }

    if (voted.dislike) {
      return cancelReport();
    }

    return sendReport();
  }

  const classes = ['tools'];

  if (voted.like) {
    classes.push('is-liked');
  }

  if (voted.dislike) {
    classes.push('is-disliked');
  }

  return (
    <div className={classes.join(' ')}>
      <button type="button" onClick={handleLike}>
        {voted.like
          ? <VotedIcon />
          : <VoteIcon />
        }
      </button>

      <button type="button" onClick={toggleComments}>
        <DiscussIcon />
      </button>

      <button type="button" onClick={handleDislike}>
        {voted.dislike
          ? <VotedIcon />
          : <VoteIcon />
        }
      </button>
    </div>
  );
}

export default Tools;