import { useState } from 'react';

import Toggler from '../Toggler';
import Navbar from '../Navbar';

import './styles.scss';

const Menu = function() {
  const [isOpened, setIsOpened] = useState(false);

  return (
    <>
      <Toggler isOpened={isOpened} setIsOpened={setIsOpened} />

      {isOpened &&
        <Navbar setIsOpened={setIsOpened} />
      }
    </>
  );
}

export default Menu;