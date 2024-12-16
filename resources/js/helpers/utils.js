export function create_options(data) {
  return Object.entries(data)
    .map(([key, value]) => ({ 'value': key, 'label': value }));
}

export function create_options_from_technicians(items) {
  return items.map((technician) => {
    return { 'value': technician.id, 'label': `${technician.username} - ${technician.name}` };
  });
}

export function create_options_from_customers(items) {
  return items.map((customer) => {
    return { 'value': customer.id, 'label': `#${customer.id}: ${customer.name}` };
  });
}

export async function scrollToFirstErrorField(ref) {
  const element = ref.getNativeElement();
  if (element) {
    element.scrollIntoView({ behavior: 'smooth', block: 'center' });
    element.focus();
  }
}
