import { useContext } from 'react';

import API from '../../api';
import GameContext from '../../context';

import Logo from '../../images/logo.png';

import './styles.scss';

const Header = function({current, disablePage}) {
  const token = useContext(GameContext);

  async function setViewed() {
    document.body.classList.add('is-out');

    const item = current.item_id;

    try {
      await API.setViewed(token, item, 'skip');
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