import { useState } from 'react';

import './styles.scss';

const DiscussItem = function({comment}) {
  const [loaded, setLoaded] = useState(false);

  function showAvatar() {
    setLoaded(true)
  }

  const classes = ['discuss-item'];

  if (loaded) {
    classes.push('is-loaded');
  }

  return (

    <div className={classes.join(' ')}>
        <img src={comment.avatar} alt={comment.name} onLoad={showAvatar} />
        <span>{comment.name}</span>
        <p>{comment.message}</p>
    </div>
  );
}

export default DiscussItem;