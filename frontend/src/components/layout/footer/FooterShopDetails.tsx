import type { PublicSettings } from '@/features/settings/types/publicSettings';
import Logo from '../header/Logo';

const FooterShopDetails = ({ settings }: { settings: PublicSettings['settings'] }) => {
  return (
    <div className="max-w-xs space-y-4 max-[480px]:flex max-[480px]:flex-col max-[480px]:items-center">
      <Logo />
      <div className="mt-3 space-y-1 text-[13px] text-muted-foreground">
        <p>{settings.address_line1}</p>
        <p>{settings.address_line2}</p>
        <p>{settings.city}</p>
        <p>
          <a href={`mailto:${settings.email}`} className="hover:text-foreground">
            {settings.email}
          </a>
        </p>
        <p>
          <a href={`tel:${settings.phone?.replace(/\s/g, '')}`} className="hover:text-foreground">
            {settings.phone}
          </a>
        </p>
      </div>
    </div>
  );
};

export default FooterShopDetails;
