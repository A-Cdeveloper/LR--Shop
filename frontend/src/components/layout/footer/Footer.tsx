import FooterNavigation from './FooterNavigation';
import FooterShopDetails from './FooterShopDetails';

const shop = {
  name: 'LR DemoShop',
  email: 'demo@example.com',
  phone: '+381 64 123 4567',
  addressLine1: '123 Demo Street',
  addressLine2: 'Apt 4B',
  city: 'Belgrade',
};

const Footer = () => {
  const year = new Date().getFullYear();

  return (
    <footer className="shrink-0 border-t border-border py-8">
      <div className="flex flex-row items-start justify-between gap-8 max-[480px]:flex-col max-[480px]:items-center max-[480px]:text-center">
        <FooterShopDetails shop={shop} />
        <FooterNavigation />
      </div>

      <p className="mt-8 text-center text-xs text-muted-foreground">
        © {year} {shop.name}. All rights reserved.
      </p>
    </footer>
  );
};

export default Footer;
