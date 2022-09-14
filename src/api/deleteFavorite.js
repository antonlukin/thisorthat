import axios from 'axios';
import { API_URL } from './constants';

const deleteFavorite = async (token, item) => {
  const data = new FormData();
  data.append('token', token);
  data.append('item_id', item);

  const response = await axios(API_URL + '/deleteFavorite', {
    method: 'POST',
    data: data,
  });

  return response.data.result;
}

export default deleteFavorite;