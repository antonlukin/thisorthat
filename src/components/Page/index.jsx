import './styles.scss';

const Page = function({children}) {
  return (
    <div className="page">
      {children}
    </div>
  );
}

export default Page;