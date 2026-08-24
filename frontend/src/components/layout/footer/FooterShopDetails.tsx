import Logo from '../header/Logo';

export type Shop = {
  name: string;
  email: string;
  phone: string;
  addressLine1: string;
  addressLine2: string;
  city: string;
};

const FooterShopDetails = ({ shop }: { shop: Shop }) => {
  return (
    <div className="max-w-xs space-y-4 max-[480px]:flex max-[480px]:flex-col max-[480px]:items-center">
      <Logo />
      <div className="mt-3 space-y-1 text-[13px] text-muted-foreground">
        <p>{shop.addressLine1}</p>
        <p>{shop.addressLine2}</p>
        <p>{shop.city}</p>
        <p>
          <a href={`mailto:${shop.email}`} className="hover:text-foreground">
            {shop.email}
          </a>
        </p>
        <p>
          <a href={`tel:${shop.phone.replace(/\s/g, '')}`} className="hover:text-foreground">
            {shop.phone}
          </a>
        </p>
      </div>
    </div>
  );
};

export default FooterShopDetails;
