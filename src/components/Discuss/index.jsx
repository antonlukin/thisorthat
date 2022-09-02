import { useEffect, useRef, useState, useContext } from 'react';

import API from '../../api';
import AuthContext from '../../context';

import DiscussItem from '../DiscussItem';
import DiscussForm from '../DiscussForm';

import './styles.scss';

const Discuss = function({current}) {
  const [comments, setComments] = useState(null);
  const discussRef = useRef(null);
  const token = useContext(AuthContext);

  useEffect(() => {
    discussRef.current.scrollIntoView({ behavior: "smooth" });
  }, [comments]);

  useEffect(() => {
    async function getComments() {
      try {
        const data = await API.getComments(token, current.item_id);
        // const data = await API.getComments(token, 354225);
        setComments(data);
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
      <DiscussForm current={current} addComment={addComment} />

      {comments && comments.reverse().map((comment) =>
        <DiscussItem comment={comment} key={comment.comment_id} />
      )}
    </div>
  );
}

export default Discuss;