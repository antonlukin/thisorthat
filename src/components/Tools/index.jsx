import { useContext } from 'react';

import AuthContext from '../../context';
import API from '../../api';

import './styles.scss';

const Tools = function({current, toggleComments}) {
  const token = useContext(AuthContext);

  function addFavorite() {
  }

  return (
    <div className="tools">
      <button type="button" className="like" onClick={addFavorite}>Нравится</button>
      <button type="button" className="replies" onClick={toggleComments}>Комментарии</button>
      <button type="button" className="dislike">Не нравится</button>
    </div>
  );
}

export default Tools;