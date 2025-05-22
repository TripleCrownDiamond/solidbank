export interface Region {
  name: string;
  code: string; // Optional, e.g., state code for US
}

export interface Country {
  name: string;
  code: string; // ISO 3166-1 alpha-2 code
  phoneCode: string; // International phone code
  regions?: Region[];
}

export const countries: Country[] = [
  // Europe
  { name: 'Albania', code: 'AL', phoneCode: '+355' },
  { name: 'Andorra', code: 'AD', phoneCode: '+376' },
  { name: 'Austria', code: 'AT', phoneCode: '+43' },
  { name: 'Belarus', code: 'BY', phoneCode: '+375' },
  { name: 'Belgium', code: 'BE', phoneCode: '+32' },
  { name: 'Bosnia and Herzegovina', code: 'BA', phoneCode: '+387' },
  { name: 'Bulgaria', code: 'BG', phoneCode: '+359' },
  { name: 'Croatia', code: 'HR', phoneCode: '+385' },
  { name: 'Cyprus', code: 'CY', phoneCode: '+357' },
  { name: 'Czech Republic', code: 'CZ', phoneCode: '+420' },
  { name: 'Denmark', code: 'DK', phoneCode: '+45' },
  { name: 'Estonia', code: 'EE', phoneCode: '+372' },
  { name: 'Finland', code: 'FI', phoneCode: '+358' },
  { name: 'France', code: 'FR', phoneCode: '+33' },
  { name: 'Germany', code: 'DE', phoneCode: '+49' },
  { name: 'Greece', code: 'GR', phoneCode: '+30' },
  { name: 'Hungary', code: 'HU', phoneCode: '+36' },
  { name: 'Iceland', code: 'IS', phoneCode: '+354' },
  { name: 'Ireland', code: 'IE', phoneCode: '+353' },
  { name: 'Italy', code: 'IT', phoneCode: '+39' },
  { name: 'Latvia', code: 'LV', phoneCode: '+371' },
  { name: 'Liechtenstein', code: 'LI', phoneCode: '+423' },
  { name: 'Lithuania', code: 'LT', phoneCode: '+370' },
  { name: 'Luxembourg', code: 'LU', phoneCode: '+352' },
  { name: 'Malta', code: 'MT', phoneCode: '+356' },
  { name: 'Moldova', code: 'MD', phoneCode: '+373' },
  { name: 'Monaco', code: 'MC', phoneCode: '+377' },
  { name: 'Montenegro', code: 'ME', phoneCode: '+382' },
  { name: 'Netherlands', code: 'NL', phoneCode: '+31' },
  { name: 'North Macedonia', code: 'MK', phoneCode: '+389' },
  { name: 'Norway', code: 'NO', phoneCode: '+47' },
  { name: 'Poland', code: 'PL', phoneCode: '+48' },
  { name: 'Portugal', code: 'PT', phoneCode: '+351' },
  { name: 'Romania', code: 'RO', phoneCode: '+40' },
  { name: 'Russia', code: 'RU', phoneCode: '+7' },
  { name: 'San Marino', code: 'SM', phoneCode: '+378' },
  { name: 'Serbia', code: 'RS', phoneCode: '+381' },
  { name: 'Slovakia', code: 'SK', phoneCode: '+421' },
  { name: 'Slovenia', code: 'SI', phoneCode: '+386' },
  { name: 'Spain', code: 'ES', phoneCode: '+34' },
  { name: 'Sweden', code: 'SE', phoneCode: '+46' },
  { name: 'Switzerland', code: 'CH', phoneCode: '+41' },
  { name: 'Turkey', code: 'TR', phoneCode: '+90' },
  { name: 'Ukraine', code: 'UA', phoneCode: '+380' },
  { name: 'United Kingdom', code: 'GB', phoneCode: '+44' },
  { name: 'Vatican City', code: 'VA', phoneCode: '+379' },
  // North America
  {
    name: 'United States',
    code: 'US',
    phoneCode: '+1',
    regions: [
      { name: 'Alabama', code: 'AL' },
      { name: 'Alaska', code: 'AK' },
      { name: 'Arizona', code: 'AZ' },
      { name: 'Arkansas', code: 'AR' },
      { name: 'California', code: 'CA' },
      { name: 'Colorado', code: 'CO' },
      { name: 'Connecticut', code: 'CT' },
      { name: 'Delaware', code: 'DE' },
      { name: 'Florida', code: 'FL' },
      { name: 'Georgia', code: 'GA' },
      { name: 'Hawaii', code: 'HI' },
      { name: 'Idaho', code: 'ID' },
      { name: 'Illinois', code: 'IL' },
      { name: 'Indiana', code: 'IN' },
      { name: 'Iowa', code: 'IA' },
      { name: 'Kansas', code: 'KS' },
      { name: 'Kentucky', code: 'KY' },
      { name: 'Louisiana', code: 'LA' },
      { name: 'Maine', code: 'ME' },
      { name: 'Maryland', code: 'MD' },
      { name: 'Massachusetts', code: 'MA' },
      { name: 'Michigan', code: 'MI' },
      { name: 'Minnesota', code: 'MN' },
      { name: 'Mississippi', code: 'MS' },
      { name: 'Missouri', code: 'MO' },
      { name: 'Montana', code: 'MT' },
      { name: 'Nebraska', code: 'NE' },
      { name: 'Nevada', code: 'NV' },
      { name: 'New Hampshire', code: 'NH' },
      { name: 'New Jersey', code: 'NJ' },
      { name: 'New Mexico', code: 'NM' },
      { name: 'New York', code: 'NY' },
      { name: 'North Carolina', code: 'NC' },
      { name: 'North Dakota', code: 'ND' },
      { name: 'Ohio', code: 'OH' },
      { name: 'Oklahoma', code: 'OK' },
      { name: 'Oregon', code: 'OR' },
      { name: 'Pennsylvania', code: 'PA' },
      { name: 'Rhode Island', code: 'RI' },
      { name: 'South Carolina', code: 'SC' },
      { name: 'South Dakota', code: 'SD' },
      { name: 'Tennessee', code: 'TN' },
      { name: 'Texas', code: 'TX' },
      { name: 'Utah', code: 'UT' },
      { name: 'Vermont', code: 'VT' },
      { name: 'Virginia', code: 'VA' },
      { name: 'Washington', code: 'WA' },
      { name: 'West Virginia', code: 'WV' },
      { name: 'Wisconsin', code: 'WI' },
      { name: 'Wyoming', code: 'WY' },
    ],
  },
  { name: 'Canada', code: 'CA', phoneCode: '+1' },
  { name: 'Mexico', code: 'MX', phoneCode: '+52' },
];

// Helper function to get regions for a country code
export const getRegionsByCountryCode = (countryCode: string): Region[] | undefined => {
  const country = countries.find(c => c.code === countryCode);
  return country?.regions;
};

// Helper function to get phone code for a country code
export const getPhoneCodeByCountryCode = (countryCode: string): string | undefined => {
  const country = countries.find(c => c.code === countryCode);
  return country?.phoneCode;
};