import Page from '../components/Page';
import Game from '../components/Game';
import Menu from '../components/Menu';
import Background from '../components/Background';

const Home = function() {
  return (
    <>
      <Menu />
      <Page>
        <Game />
        <Background />
      </Page>
    </>
  );
}

export default Home;