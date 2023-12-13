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
          Игра была запущена в 2014 году. За это время более миллиона пользователей добавили почти 200 тысяч вопросов.
          Всего в базе хранится больше 165 миллионов ответов.
        </p>

        <p>
          Сейчас вы можете задать свой вопрос только в приложениях под <a href="https://play.google.com/store/apps/details?id=com.svobnick.thisorthat" target="_blank" rel="noreferrer">Android</a> или <a href="https://apps.apple.com/us/app/id963727722" target="_blank" rel="noreferrer">iOS</a>.
        </p>

        <p>
          Если вам не нравится вопрос, пометьте его пальцем вниз на сайте, либо отправьте жалобу в приложении.
          Через некоторое время, если вопрос наберет много негативных отзывов, он автоматически удалится из базы.
          Нажмите на логотип, если хотите пропустить вопрос, не отвечая на него.
        </p>

        <p>
          Вы также можете принять участие в модерации новых вопросов через соотвествующий раздел на сайте.
          Будущее игры зависит только от вас!
        </p>
      </Content>
    </Page>
  );
}

export default About;