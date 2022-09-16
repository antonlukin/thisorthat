import { useContext } from 'react';

import API from '../../api';
import GameContext from '../../context';

import Logo from '../../images/logo.png';

import './styles.scss';

const Header = function({current}) {
  const token = useContext(GameContext);

  async function setViewed() {
    try {
      await API.setViewed(token, current.item_id, 'skip');
    } catch (error) {
      console.error(error);
    }

    window.location.reload();
  }

  return (
    <button className="header" onClick={setViewed}>
      <img src={Logo} alt="То или Это"/>
    </button>
  );
}

export default Header;