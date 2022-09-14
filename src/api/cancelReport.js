import axios from 'axios';

const cancelReport = async (token, item) => {
  const data = new FormData();
  data.append('token', token);
  data.append('item_id', item);

  const response = await axios('https://api.thisorthat.ru/cancelReport', {
    method: 'POST',
    data: data,
  });

  return response.data.result;
}

export default cancelReport;