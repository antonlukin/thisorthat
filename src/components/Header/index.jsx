import { Link } from "react-router-dom";

import Logo from '../../images/logo.png';

import './styles.scss';

const Header = function() {
  return (
    <Link to="/" className="header">
      <img src={Logo} alt="То или Это"/>
    </Link>
  );
}

export default Header;