import { Link } from 'react-router-dom';
import { ReactComponent as BackIcon } from '../../images/back.svg';

import './styles.scss';

const Backlink = function({children}) {
  return (
    <div className="backlink">
      <Link to="/">
        <BackIcon />
      </Link>

      <h1>{children}</h1>
    </div>
  );
}

export default Backlink;