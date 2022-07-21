import Header from '../Header';
import Background from '../Background'
import Questions from '../Questions';
import Tools from '../Tools';
import Discuss from '../Discuss';

import './styles.scss';

const Game = function() {
  return (
    <>
      <div className="game">
        <Header />
        <Questions />
        <Tools />

        <Discuss/>
      </div>

      <Background />
    </>
  );
}

export default Game;