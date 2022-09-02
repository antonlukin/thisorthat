import Page from '../components/Page';
import Content from '../components/Content';
import Backlink from '../components/Backlink';
import Moderation from '../components/Moderation';
import Background from '../components/Background';

const Admin = function() {
  document.documentElement.style.setProperty('--app-height', `${window.innerHeight}px`);

  return (
    <Page>
      <Backlink>Модерация вопросов</Backlink>

      <Content>
        <p>
          В этом разделе отображаются новые вопросы от пользоваталей.
          Вы можете принять участие в модерации.
          Голосуйте за понравившиеся вопросы, и они появятся в основном разделе.
        </p>
      </Content>

      <Moderation />

      <Background />
    </Page>
  );
}

export default Admin;