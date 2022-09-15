import { useEffect } from 'react';

import Page from '../components/Page';
import Content from '../components/Content';
import Backlink from '../components/Backlink';
import Feedback from '../components/Feedback';

const Contacts = function({setLoader}) {
  useEffect(() => {
    setLoader(false);
  });

  return (
    <Page>
      <Backlink>Обратная связь</Backlink>

      <Content>
        <p>
          Свяжитесь с нами, если хотите сообщить об ошибке или посоветовать улучшение.
          Мы будем рады любой помощи или предложению.
          Оставьте свою почту или ссылку на Telegram, если ждете от нас ответа.
        </p>

        <p>
          <strong>Заявки на добавление новых вопросов рассматриваться не будут.</strong>
        </p>
      </Content>

      <Feedback />
    </Page>
  );
}

export default Contacts;