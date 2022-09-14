import axios from 'axios';

const setViewed = async (token, item, result) => {
  const data = new FormData();
  data.append('token', token);
  data.append('views[' + item + ']', result);

  const response = await axios.post('https://api.thisorthat.ru/setViewed', data);

  return response.data.result;
}

export default setViewed;