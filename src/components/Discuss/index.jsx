import { useEffect, useRef, useState, useContext } from 'react';
import smoothScroll from '../../utils/scroller';

import API from '../../api';
import GameContext from '../../context';

import DiscussItem from '../DiscussItem';
import DiscussForm from '../DiscussForm';

import './styles.scss';

const Discuss = function({current}) {
  const [comments, setComments] = useState([]);
  const discussRef = useRef(null);

  const token = useContext(GameContext);

  useEffect(() => {
    discussRef.current.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }, []);

  useEffect(() => {
    if (comments.length > 0) {
      smoothScroll(document.body.scrollHeight);
    }
  }, [comments]);

  useEffect(() => {
    async function getComments() {
      try {
        setComments(await API.getComments(token, current.item_id));
      } catch(error) {
        setComments([]);
      }
    }

    getComments();
  }, [current, token]);

  function addComment(response) {
    setComments(comments.concat(response));
  }

  return (
    <div className="discuss" ref={discussRef}>
      {comments && comments.map((comment) =>
        <DiscussItem comment={comment} key={comment.comment_id} />
      )}

      <DiscussForm current={current} addComment={addComment} />
    </div>
  );
}

export default Discuss;