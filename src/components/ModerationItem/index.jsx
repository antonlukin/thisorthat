import { useState, useEffect, useContext } from 'react';

import { ReactComponent as DeclineIcon } from '../../images/decline.svg';
import { ReactComponent as ApproveIcon } from '../../images/approve.svg';

import API from '../../api';
import GameContext from '../../context';

import './styles.scss';

const ModerationItem = function({item, removeItem}) {
  const [result, setResult] = useState(null);

  const token = useContext(GameContext);

  useEffect(() => {
    async function setAudit() {
      try {
        await API.setAudit(token, item.item_id, result);
      } catch (error) {
        console.error(error);
      }
    }

    if (result) {
      setAudit();
    }
  }, [result, token, item])

  function approveItem() {
    setResult('approve');

    setTimeout(() => {
      removeItem(item.item_id);
    }, 500);
  }

  function declineItem() {
    setResult('decline');

    setTimeout(() => {
      removeItem(item.item_id);
    }, 500);
  }

  const classes = ['moderation-item'];

  if (result) {
    classes.push('is-voted');
  }

  return (
    <div className={classes.join(' ')}>
      <ul>
        <li>{item.first_text}</li>
        <li>{item.last_text}</li>
      </ul>

      <fieldset>
        <button onClick={approveItem}>
          <ApproveIcon />
          <span>Принять</span>
        </button>

        <button onClick={declineItem}>
          <DeclineIcon />
          <span>Отклонить</span>
        </button>
      </fieldset>
    </div>
  );
}

export default ModerationItem;