import { useEffect } from 'react';

import Page from '../components/Page';
import Content from '../components/Content';
import Backlink from '../components/Backlink';

const About = function({setLoader}) {
  useEffect(() => {
    setLoader(false);
  });

  return (
    <Page>
      <Backlink>О проекте</Backlink>

      <Content>
        <p>
          <strong>То или Это</strong> – игра, в которой пользователи придумывают вопросы и делают непростой выбор.
          Решайте, какой из двух вариантов вам подходит, и смотрите, сколько человек ответили так же.
        </p>

        <p>
          Сейчас вы можете задать свой вопрос только в приложении под <a href="https://play.google.com/store/apps/details?id=com.svobnick.thisorthat" target="_blank" rel="noreferrer">Android</a>.
        </p>

        <p>
          Если вам не нравится вопрос, пометьте его пальцем вниз на сайте, либо отправьте жалобу в приложении.
          Через некоторое время, если вопрос наберет много негативных отзывов, он автоматически удалится из базы.
        </p>

        <p>
          Вы также можете участие в модерации новых вопросов через соотвествующий раздел на сайте.
        </p>

        <p>
          Чтобы связаться с нами, напишите <a href="https://t.me/thisorthat_robot" target="_blank" rel="noreferrer">Telegram боту</a>.
          Мы будем признательны за обратную связь по работе приложения и сайта.
        </p>

        <p>
          <strong>Заявки на добавление новых вопросов рассматриваться не будут.</strong>
        </p>
      </Content>
    </Page>
  );
}

export default About;