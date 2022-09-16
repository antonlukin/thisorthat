import { useEffect } from 'react';

import Warning from '../components/Warning';

const Notfound = function({setLoader}) {
  useEffect(() => {
    setLoader(false);
  });

  function redirectUser() {
    document.location.href = '/';
  }

  return (
    <Warning position="on-fullscreen">
      <strong>Ничего не найдено</strong>
      <span>Попробуйте начать поиск <button onClick={redirectUser}>с главной страницы</button>.</span>
    </Warning>
  );
}

export default Notfound;