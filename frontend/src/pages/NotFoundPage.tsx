import { Button } from '@/components/ui/button';
import { useNavigate } from 'react-router-dom';

const NotFoundPage = () => {
  const navigate = useNavigate();

  return (
    <>
      <h1 className="text-6xl font-bold">404</h1>
      <p className="text-lg">Page not found</p>

      <Button onClick={() => navigate('/')}>Back to Home</Button>
    </>
  );
};

export default NotFoundPage;
