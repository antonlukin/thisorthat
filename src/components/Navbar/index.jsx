import { Link } from 'react-router-dom';

import { ReactComponent as AboutIcon } from '../../images/about.svg';
import { ReactComponent as AdminIcon } from '../../images/admin.svg';
import { ReactComponent as ReportIcon } from '../../images/report.svg';
import { ReactComponent as LogoutIcon } from '../../images/logout.svg';

import './styles.scss';

const Navbar = function({setIsOpened}) {
  function resetToken() {
    window.localStorage.removeItem('token');
    window.location.reload();
  }

  function hideNavbar() {
    setIsOpened(false);
  }

  function stopClick(e) {
    e.stopPropagation();
  }

  return (
    <div className="navbar" onClick={hideNavbar}>
      <nav onClick={stopClick}>
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