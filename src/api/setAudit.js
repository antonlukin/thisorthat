import axios from 'axios';

const setAudit = async (token, item, result) => {
  const data = new FormData();
  data.append('token', token);
  data.append('item_id', item);
  data.append('vote', result);

  const response = await axios.post('https://api.thisorthat.ru/setAudit', data);

  return response.data.result;
}

export default setAudit;