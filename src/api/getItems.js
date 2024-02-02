import axios from 'axios';
import { API_URL } from './constants';

const getItems = async (token) => {
  const data = new FormData();
  data.append('token', token);
  data.append('status', 'approved');

  const response = await axios(API_URL + '/getItems', {
    method: 'POST',
    data: data,
  });


  if ('umami' in window) {
    window.umami.track('get-items');
  }

  return response.data.result?.items;
}

export default getItems;