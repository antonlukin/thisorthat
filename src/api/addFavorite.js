import axios from 'axios';
import { API_URL } from './constants';

const addFavorite = async (token, item) => {
  const data = new FormData();
  data.append('token', token);
  data.append('item_id', item);

  const response = await axios(API_URL + '/addFavorite', {
    method: 'POST',
    data: data,
  });

  return response.data.result;
}

export default addFavorite;