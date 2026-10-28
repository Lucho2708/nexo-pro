import { computed } from 'vue';

export interface GeographyOption {
    value: string;
    label: string;
}

export const COLOMBIA_GEOGRAPHY: Record<string, string[]> = {
    'Amazonas': ['Leticia', 'Puerto Nariño'],
    'Antioquia': [
        'Medellín', 'Bello', 'Itagüí', 'Envigado', 'Apartadó', 'Rionegro', 'Turbo', 'Caucasia', 'Caldas',
        'Chigorodó', 'Copacabana', 'La Estrella', 'Girardota', 'Marinilla', 'Guarne', 'Santa Fe de Antioquia',
        'El Carmen de Viboral', 'Yarumal', 'Andes', 'Puerto Berrío', 'Sabaneta', 'El Retiro', 'La Ceja', 'Guatapé'
    ],
    'Arauca': ['Arauca', 'Arauquita', 'Saravena', 'Tame', 'Fortul'],
    'Atlántico': [
        'Barranquilla', 'Soledad', 'Malambo', 'Sabanalarga', 'Baranoa', 'Puerto Colombia', 'Galapa',
        'Palmar de Varela', 'Santo Tomás', 'Tubará'
    ],
    'Bogotá D.C.': ['Bogotá D.C.'],
    'Bolívar': [
        'Cartagena de Indias', 'Magangué', 'El Carmen de Bolívar', 'Turbaco', 'Arjona', 'María La Baja',
        'San Juan Nepomuceno', 'Mompós', 'Turbana'
    ],
    'Boyacá': [
        'Tunja', 'Duitama', 'Sogamoso', 'Chiquinquirá', 'Puerto Boyacá', 'Paipa', 'Moniquirá', 'Villa de Leyva',
        'Garagoa', 'Nobsa', 'Samacá'
    ],
    'Caldas': ['Manizales', 'La Dorada', 'Chinchiná', 'Villamaría', 'Riosucio', 'Anserma', 'Salamina', 'Aguadas'],
    'Caquetá': ['Florencia', 'San Vicente del Caguán', 'Cartagena del Chairá', 'Puerto Rico', 'El Doncello'],
    'Casanare': ['Yopal', 'Aguazul', 'Villanueva', 'Tauramena', 'Monterrey', 'Paz de Ariporo'],
    'Cauca': ['Popayán', 'Santander de Quilichao', 'Puerto Tejada', 'Patía', 'Piendamó', 'El Tambo', 'Corinto'],
    'Cesar': ['Valledupar', 'Aguachica', 'Agustín Codazzi', 'Bosconia', 'Curumaní', 'La Paz', 'Chiriguaná'],
    'Chocó': ['Quibdó', 'Istmina', 'Condoto', 'Tadó', 'Acandí', 'Bahía Solano', 'Nuquí'],
    'Córdoba': [
        'Montería', 'Cereté', 'Lorica', 'Sahagún', 'Montelíbano', 'Planeta Rica', 'Ciénaga de Oro', 'Tierralta'
    ],
    'Cundinamarca': [
        'Soacha', 'Facatativá', 'Chía', 'Zipaquirá', 'Fusagasugá', 'Mosquera', 'Madrid', 'Funza', 'Cajicá',
        'Girardot', 'Tocancipá', 'Sopó', 'Cota', 'La Calera', 'Sibaté', 'Tabio', 'Tenjo', 'Villeta', 'Ubaté'
    ],
    'Guainía': ['Inírida', 'Barranco Minas'],
    'Guaviare': ['San José del Guaviare', 'El Retorno', 'Miraflores', 'Calamar'],
    'Huila': ['Neiva', 'Pitalito', 'Garzón', 'La Plata', 'Campoalegre', 'San Agustín', 'Palermo', 'Rivera'],
    'La Guajira': ['Riohacha', 'Maicao', 'Uribia', 'Manaure', 'San Juan del Cesar', 'Fonseca', 'Barrancas'],
    'Magdalena': [
        'Santa Marta', 'Ciénaga', 'Fundación', 'El Banco', 'Plato', 'Aracataca', 'Pivijay', 'Sitionuevo'
    ],
    'Meta': [
        'Villavicencio', 'Acacías', 'Granada', 'Puerto López', 'San Martín', 'Puerto Gaitán', 'Restrepo', 'Cumaral'
    ],
    'Nariño': ['Pasto', 'Tumaco', 'Ipiales', 'Samaniego', 'Túquerres', 'La Unión', 'Sandoná'],
    'Norte de Santander': [
        'Cúcuta', 'Ocaña', 'Villa del Rosario', 'Los Patios', 'Pamplona', 'Tibú', 'El Zulia', 'Chinácota'
    ],
    'Putumayo': ['Mocoa', 'Puerto Asís', 'Orito', 'Valle del Guamuez', 'Sibundoy', 'Villagarzón'],
    'Quindío': [
        'Armenia', 'Calarcá', 'La Tebaida', 'Circasia', 'Montenegro', 'Quimbaya', 'Salento', 'Filandia'
    ],
    'Risaralda': ['Pereira', 'Dosquebradas', 'Santa Rosa de Cabal', 'La Virginia', 'Belén de Umbría', 'Marsella'],
    'San Andrés y Providencia': ['San Andrés', 'Providencia'],
    'Santander': [
        'Bucaramanga', 'Floridablanca', 'Girón', 'Piedecuesta', 'Barrancabermeja', 'San Gil', 'Socorro', 'Lebrija',
        'Barbosa', 'San Vicente de Chucurí', 'Cimitarra'
    ],
    'Sucre': ['Sincelejo', 'Corozal', 'San Marcos', 'Tolú', 'Sampués', 'San Onofre', 'Coveñas'],
    'Tolima': ['Ibagué', 'Espinal', 'Melgar', 'Chaparral', 'Líbano', 'Mariquita', 'Honda', 'Flandes', 'Guamo'],
    'Valle del Cauca': [
        'Cali', 'Palmira', 'Buenaventura', 'Tuluá', 'Cartago', 'Buga', 'Jamundí', 'Yumbo', 'Candelaria',
        'Florida', 'Pradera', 'Sevilla', 'Zarzal', 'Roldanillo'
    ],
    'Vaupés': ['Mitú', 'Carurú', 'Taraira'],
    'Vichada': ['Puerto Carreño', 'La Primavera', 'Santa Rosalía', 'Cumaribo'],
};

export function useColombiaGeography() {
    const departamentosList = computed<GeographyOption[]>(() => {
        return Object.keys(COLOMBIA_GEOGRAPHY).sort((a, b) => a.localeCompare(b, 'es')).map(dep => ({
            value: dep,
            label: dep,
        }));
    });

    const getMunicipios = (departamento: string | null | undefined): GeographyOption[] => {
        if (!departamento || !COLOMBIA_GEOGRAPHY[departamento]) {
            return [];
        }
        return COLOMBIA_GEOGRAPHY[departamento]
            .slice()
            .sort((a, b) => a.localeCompare(b, 'es'))
            .map(mun => ({
                value: mun,
                label: mun,
            }));
    };

    return {
        departamentosList,
        getMunicipios,
    };
}
