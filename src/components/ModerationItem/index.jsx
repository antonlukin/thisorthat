import { useState, useEffect, useContext } from 'react';

import { ReactComponent as DeclineIcon } from '../../images/decline.svg';
import { ReactComponent as ApproveIcon } from '../../images/approve.svg';

import API from '../../api';
import AuthContext from '../../context';

import './styles.scss';

const ModerationItem = function({item}) {
  return (
    <div className="moderation-item">
      <ul>
        <li>{item.first_text}</li>
        <li>{item.last_text}</li>
      </ul>

      <fieldset>
        <button>
          <ApproveIcon />
          <span>Принять</span>
        </button>

        <button>
          <DeclineIcon />
          <span>Отклонить</span>
        </button>
      </fieldset>
    </div>
  );
}

export default ModerationItem;