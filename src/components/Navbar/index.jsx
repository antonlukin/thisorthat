import { Link } from 'react-router-dom';

import { ReactComponent as AboutIcon } from '../../images/about.svg';
import { ReactComponent as AdminIcon } from '../../images/admin.svg';
import { ReactComponent as ContactsIcon } from '../../images/contacts.svg';
import { ReactComponent as LogoutIcon } from '../../images/logout.svg';
import { ReactComponent as GoogleStoreIcon } from '../../images/googlestore.svg';
import { ReactComponent as AppStoreIcon } from '../../images/appstore.svg';

import resetToken from '../../utils/logout';

import './styles.scss';

const Navbar = function({setOpened}) {
  function hideNavbar(e) {
    if (e.target.tagName.toLowerCase() === 'nav') {
      return e.stopPropagation();
    }

    setOpened(false);
  }

  return (
    <div className="navbar" onClick={hideNavbar}>
      <nav>
        <Link to="/about">
          <AboutIcon />
          О проекте
        </Link>

        <a href="https://play.google.com/store/apps/details?id=com.svobnick.thisorthat" target="_blank" rel="noreferrer">
          <GoogleStoreIcon />
          Скачать на Android
        </a>

        <a href="https://apps.apple.com/us/app/id963727722" target="_blank" rel="noreferrer">
          <AppStoreIcon />
          Скачать на iOS
        </a>

        <Link to="/admin">
          <AdminIcon />
          Модерация вопросов
        </Link>

        <Link to="/contacts">
          <ContactsIcon />
          Написать авторам
        </Link>

        <button type="button" onClick={resetToken}>
          <LogoutIcon />
          Сбросить аккаунт
        </button>
      </nav>
    </div>
  );
}

export default Navbar;