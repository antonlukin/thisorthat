import axios from 'axios';

const sendReport = async (token, item) => {
  const data = new FormData();
  data.append('token', token);
  data.append('item_id', item);
  data.append('reason', 'dislike');

  const response = await axios('https://api.thisorthat.ru/sendReport', {
    method: 'POST',
    data: data,
  });

  return response.data.result;
}

export default sendReport;