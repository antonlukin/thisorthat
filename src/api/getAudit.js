import axios from 'axios';

const getAudit = async (token) => {
  const data = new FormData();
  data.append('token', token);
  data.append('status', 'new');

  const response = await axios('/getAudit', {
    method: 'POST',
    data: data,
  });

  return response.data.result?.items;
}

export default getAudit;