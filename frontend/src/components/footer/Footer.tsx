import { usePublicSettings } from '@/features/settings/hooks/usePublicSettings';
import FooterNavigation from './FooterNavigation';
import FooterShopDetails from './FooterShopDetails';
import Loading from '../common/Loading';
import ErrorMessages from '../common/ErrorMessages';

const Footer = () => {
  const year = new Date().getFullYear();
  const { data, isLoading, error } = usePublicSettings();

  if (isLoading) {
    return <Loading className="w-12 h-12" title="Loading footer..." />;
  }

  if (error) {
    return <ErrorMessages error={error} />;
  }

  if (!data) {
    return null;
  }

  return (
    <footer className="shrink-0 border-t border-border py-8">
      <div className="flex flex-row items-start justify-between gap-8 max-[480px]:flex-col max-[480px]:items-center max-[480px]:text-center">
        <FooterShopDetails settings={data.settings} />
        <FooterNavigation />
      </div>

      <p className="mt-8 text-center text-xs text-muted-foreground">
        © {year} {data.settings.name}. All rights reserved.
      </p>
    </footer>
  );
};

export default Footer;
