import { Link } from 'react-router-dom';

import { ReactComponent as AboutIcon } from '../../images/about.svg';
import { ReactComponent as AdminIcon } from '../../images/admin.svg';
import { ReactComponent as ReportIcon } from '../../images/report.svg';
import { ReactComponent as LogoutIcon } from '../../images/logout.svg';

import resetToken from '../../utils/logout';

import './styles.scss';

const Navbar = function({setOpened}) {
  function hideNavbar() {
    setOpened(false);
  }

  function stopClick(e) {
    e.stopPropagation();
  }

  return (
    <div className="navbar" onClick={hideNavbar}>
      <nav onClick={stopClick}>
        <Link to="/about" onClick={hideNavbar}>
          <AboutIcon />
          О проекте
        </Link>

        <Link to="/admin">
          <AdminIcon />
          Модерация вопросов
        </Link>

        <a href="https://t.me/thisorthat_robot" target="_blank" rel="noreferrer" onClick={hideNavbar}>
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