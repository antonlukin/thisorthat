import './styles.scss';
import Logo from '../../images/logo.png';

const Header = function() {
  return (
    <a href="/" className="header">
      <img src={Logo} alt="То или Это"/>
    </a>
  );
}

export default Header;