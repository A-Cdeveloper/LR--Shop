import { Button } from '@/components/ui/button';
import { useNavigate } from 'react-router-dom';

const NotFoundPage = () => {
  const navigate = useNavigate();

  return (
    <div className="flex flex-col items-center justify-center h-screen gap-4">
      <h1 className="text-6xl font-bold">404</h1>
      <p className="text-lg">Page not found</p>

      <Button onClick={() => navigate('/')}>Back to Home</Button>
    </div>
  );
};

export default NotFoundPage;
