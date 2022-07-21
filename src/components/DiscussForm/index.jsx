import { useState } from 'react';

import './styles.scss';

const DiscussForm = function() {
  const [comment, setComment] = useState('');

  const addComment = (e) => {
    e.preventDefault();

    alert(1);
  }

  return (
    <form className="discuss-form" action="/" onSubmit={addComment}>
        <input
          type="text"
          value={comment}
          onChange={(e) => setComment(e.target.value)}
          placeholder="Ваш комментарий"
        />

        {comment &&
          <button type="submit">Отправить</button>
        }
    </form>
  );
}

export default DiscussForm;