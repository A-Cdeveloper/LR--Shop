import { Link } from 'react-router-dom';

const Logo = () => {
  return (
    <Link to="/">
      <img src="/logo.png" alt="logo" className="h-13" loading="eager" />
    </Link>
  );
};

export default Logo;
