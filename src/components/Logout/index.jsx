import { ReactComponent as LogoutIcon } from '../../images/logout.svg';

import resetToken from '../../utils/logout';

import './styles.scss';

const Logout = function() {
  return (
    <button className="logout" onClick={resetToken}>
      <LogoutIcon />
    </button>
  );
}

export default Logout;