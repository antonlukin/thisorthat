import Logo from '../../images/logo.png';

import './styles.scss';

const Header = function() {
  function refreshPage() {
    window.location.reload();
  }

  return (
    <button className="header" onClick={refreshPage}>
      <img src={Logo} alt="То или Это"/>
    </button>
  );
}

export default Header;