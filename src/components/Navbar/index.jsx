import { Link } from 'react-router-dom';

import { ReactComponent as AboutIcon } from '../../images/about.svg';
import { ReactComponent as AdminIcon } from '../../images/admin.svg';
import { ReactComponent as ReportIcon } from '../../images/report.svg';
import { ReactComponent as LogoutIcon } from '../../images/logout.svg';

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

        <Link to="/admin">
          <AdminIcon />
          Модерация вопросов
        </Link>

        <a href="https://t.me/thisorthat_robot" target="_blank" rel="noreferrer">
          <ReportIcon />
          Сообщить о проблеме
        </a>

        <button type="button" onClick={resetToken}>
          <LogoutIcon />
          Сбросить аккаунт
        </button>
      </nav>
    </div>
  );
}

export default Navbar;