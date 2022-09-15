import { useState } from 'react';
import { FEEDBACK_URL } from '../../api/constants';
import TextareaAutosize from 'react-textarea-autosize';
import axios from 'axios';

import Loader from '../Loader';
import Warning from '../Warning';

import './styles.scss';

const Feedback = function() {
  const [message, setMessage] = useState({name: '', body: ''});
  const [warning, setWarning] = useState('');
  const [loading, setLoading] = useState(false);

  async function submitForm(e) {
    e.preventDefault();

    if (!message.body) {
      return;
    }

    setWarning('');
    setLoading(true);

    const payload = [message.body];

    if (message.name) {
      payload.push(`<b>${message.name}</b>`);
    }

    const data = new FormData();
    data.append('text', payload.join('\n\n'));

    try {
      await axios(FEEDBACK_URL + '/feedback/', {
        method: 'POST',
        data: data,
      });

      setMessage({name: '', body: ''});
      setWarning('Сообщение успешно отправлено');
    } catch (error) {
      setWarning('Не удалось отправить сообщение');
    }

    setLoading(false);
  }

  function updateName(e) {
    if (!loading) {
      setMessage({...message, name: e.target.value});
    }

    setWarning('');
  }

  function updateBody(e) {
    if (!loading) {
      setMessage({...message, body: e.target.value});
    }

    setWarning('');
  }

  return (
    <form className="feedback" action="/" onSubmit={submitForm}>
      <input
        type="text"
        value={message.name}
        maxLength={200}
        onChange={updateName}
        placeholder="Как с вами связаться"
      />

      <TextareaAutosize
        value={message.body}
        maxLength={800}
        onChange={updateBody}
        placeholder="Ваш комментарий"
      />

      <fieldset>
        {warning
          ? <Warning position="on-feedback">{warning}</Warning>
          : <button type="submit">Отправить</button>
        }
        {loading &&
          <Loader position="on-feedback" />
        }
      </fieldset>
    </form>
  );
}

export default Feedback;