import { useState } from 'react';

import Toggler from '../Toggler';
import Navbar from '../Navbar';

import './styles.scss';

const Menu = function() {
  const [opened, setOpened] = useState(false);

  return (
    <>
      <Toggler opened={opened} setOpened={setOpened} />

      {opened &&
        <Navbar setOpened={setOpened} />
      }
    </>
  );
}

export default Menu;