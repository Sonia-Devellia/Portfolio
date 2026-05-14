<?php

declare(strict_types=1);

/**
 * Contexte local par ville — pour éviter le thin content sur les pages géo.
 *
 * Chaque entrée fournit :
 *  - headline_fr / headline_en : phrase d'accroche unique à la ville
 *  - ecosystem_fr / ecosystem_en : paragraphe sur l'écosystème tech / économique local
 *  - sectors : 3-4 secteurs phares (servent aussi à enrichir le JSON-LD knowsAbout)
 *
 * Faits stables uniquement (institutions reconnues, secteurs économiques majeurs,
 * événements récurrents) — pas d'entreprises spécifiques qui peuvent fermer.
 */

return [

    // ═══════════════════════════════════════════════════════════════════
    // Grand Ouest — Bretagne
    // ═══════════════════════════════════════════════════════════════════

    'rennes' => [
        'headline_fr' => 'Rennes, capitale française de la cybersécurité — un écosystème logiciel en pleine maturité.',
        'headline_en' => 'Rennes, French cybersecurity capital — a software ecosystem reaching maturity.',
        'ecosystem_fr' => 'Rennes abrite le Pôle d\'Excellence Cyber, l\'IRISA, l\'INRIA Bretagne, et l\'INSA. La ville concentre une densité rare d\'ingénieurs logiciels, d\'experts cyber et de chercheurs IA, avec un tissu PME tech mature autour du quartier Beaulieu et de la Cité de la cybersécurité.',
        'ecosystem_en' => 'Rennes hosts the French Cybersecurity Excellence Cluster, IRISA, INRIA Bretagne, and INSA. The city concentrates a rare density of software engineers, cyber experts and AI researchers, with a mature tech SME ecosystem around the Beaulieu campus and the Cybersecurity Hub.',
        'sectors' => ['Cybersécurité', 'Télécoms', 'IA / Recherche', 'SaaS B2B'],
    ],

    'nantes' => [
        'headline_fr' => 'Nantes, première destination tech du Grand Ouest — entre Web2Day et FrenchTech Atlantique.',
        'headline_en' => 'Nantes, top tech destination in western France — home of Web2Day and FrenchTech Atlantique.',
        'ecosystem_fr' => 'Nantes accueille chaque année Web2Day, la grand-messe du numérique de l\'Ouest, et porte une scène SaaS B2B reconnue. L\'écosystème mêle GreenTech, mobilité, e-commerce et plateformes — soutenu par Atlanpole, l\'École Centrale et Polytech Nantes.',
        'ecosystem_en' => 'Nantes hosts Web2Day every year, western France\'s flagship digital event, and runs a recognised B2B SaaS scene. The ecosystem mixes GreenTech, mobility, e-commerce and platforms — backed by Atlanpole, École Centrale and Polytech Nantes.',
        'sectors' => ['SaaS B2B', 'GreenTech', 'Mobilité', 'E-commerce'],
    ],

    'vannes' => [
        'headline_fr' => 'Vannes, ma ville — Bretagne Sud, French Tech Atlantic Valley, écosystème à taille humaine.',
        'headline_en' => 'Vannes, my hometown — South Brittany, French Tech Atlantic Valley, ecosystem on a human scale.',
        'ecosystem_fr' => 'Vannes s\'inscrit dans le réseau French Tech Atlantic Valley aux côtés de Lorient et Quimper. La ville mêle économie maritime, agritech, agences digitales et PME logicielles, avec un tissu de freelances seniors orienté code propre, sécurité et durabilité technique.',
        'ecosystem_en' => 'Vannes is part of the French Tech Atlantic Valley network alongside Lorient and Quimper. The city blends maritime economy, agritech, digital agencies and software SMEs, with a fabric of senior freelancers focused on clean code, security and technical sustainability.',
        'sectors' => ['Économie maritime', 'Agritech', 'Web / Digital', 'PME logicielles'],
    ],

    'brest' => [
        'headline_fr' => 'Brest, pôle d\'excellence maritime et numérique — IMT Atlantique, Ifremer, Naval Group.',
        'headline_en' => 'Brest, maritime and digital excellence cluster — IMT Atlantique, Ifremer, Naval Group.',
        'ecosystem_fr' => 'Brest concentre la recherche océanographique française autour d\'Ifremer et de l\'IUEM, et forme des ingénieurs logiciels via IMT Atlantique et l\'ENSTA Bretagne. L\'écosystème mêle maritime tech, défense, télécoms et logiciels embarqués — avec une forte culture open source.',
        'ecosystem_en' => 'Brest concentrates France\'s oceanographic research around Ifremer and IUEM, and trains software engineers through IMT Atlantique and ENSTA Bretagne. The ecosystem mixes maritime tech, defence, telecoms and embedded software — with a strong open source culture.',
        'sectors' => ['Maritime tech', 'Défense', 'Télécoms', 'Logiciels embarqués'],
    ],

    'quimper' => [
        'headline_fr' => 'Quimper, porte d\'entrée de la Cornouaille — agroalimentaire numérique et économie circulaire.',
        'headline_en' => 'Quimper, gateway to Cornouaille — digital food industry and circular economy.',
        'ecosystem_fr' => 'Quimper s\'appuie sur un tissu industriel agroalimentaire en pleine digitalisation (traçabilité, supply chain, e-commerce B2B). La ville accueille des PME tech à taille familiale et bénéficie de la dynamique French Tech Atlantic Valley aux côtés de Lorient et Vannes.',
        'ecosystem_en' => 'Quimper relies on a food industry ecosystem undergoing rapid digitalisation (traceability, supply chain, B2B e-commerce). The city welcomes family-sized tech SMEs and benefits from the French Tech Atlantic Valley dynamic alongside Lorient and Vannes.',
        'sectors' => ['Agroalimentaire numérique', 'Supply chain', 'PME industrielles', 'E-commerce B2B'],
    ],

    'lorient' => [
        'headline_fr' => 'Lorient, capitale française de la voile compétition — entre Sea Tech Week et Lorient La Base.',
        'headline_en' => 'Lorient, French capital of competitive sailing — between Sea Tech Week and Lorient La Base.',
        'ecosystem_fr' => 'Lorient porte la Sea Tech Week, événement européen majeur sur les sciences et technologies de la mer, et concentre l\'industrie nautique haute performance. L\'écosystème logiciel local s\'oriente vers la navale, la défense, et les capteurs maritimes connectés.',
        'ecosystem_en' => 'Lorient hosts Sea Tech Week, a major European event on marine sciences and technology, and concentrates the high-performance nautical industry. The local software ecosystem focuses on naval, defence, and connected maritime sensors.',
        'sectors' => ['Maritime tech', 'IoT industriel', 'Défense navale', 'Voile compétition'],
    ],

    'saint-brieuc' => [
        'headline_fr' => 'Saint-Brieuc, cœur des Côtes-d\'Armor — agroalimentaire, banque mutualiste, économie de proximité.',
        'headline_en' => 'Saint-Brieuc, heart of Côtes-d\'Armor — food industry, mutual banking, local economy.',
        'ecosystem_fr' => 'Saint-Brieuc s\'appuie sur l\'agroalimentaire breton, le tissu mutualiste bancaire et des PME industrielles en transformation numérique. La demande locale en développement web touche surtout des projets sectoriels — gestion, e-commerce, espace client — avec un horizon multi-années.',
        'ecosystem_en' => 'Saint-Brieuc draws on Brittany\'s food industry, the cooperative banking sector and industrial SMEs in digital transformation. Local web development demand is mostly about sector-specific projects — back-office, e-commerce, client portals — with a multi-year horizon.',
        'sectors' => ['Agroalimentaire', 'Back-office métier', 'E-commerce', 'PME industrielles'],
    ],

    'saint-malo' => [
        'headline_fr' => 'Saint-Malo, cité corsaire — tourisme premium, croisières, économie maritime patrimoniale.',
        'headline_en' => 'Saint-Malo, corsair city — premium tourism, cruises, heritage maritime economy.',
        'ecosystem_fr' => 'Saint-Malo combine un tissu hôtelier-restauration exigeant, des opérateurs de croisière et un écosystème de PME orientées tourisme expérientiel. Les besoins tech locaux portent sur les espaces réservation, la billetterie, et les sites multilingues à forte saisonnalité.',
        'ecosystem_en' => 'Saint-Malo combines a demanding hotel and restaurant ecosystem, cruise operators, and SMEs focused on experiential tourism. Local tech needs centre on booking platforms, ticketing, and multilingual seasonal websites.',
        'sectors' => ['Tourisme premium', 'Booking & réservation', 'Croisière', 'Sites multilingues'],
    ],

    // ═══════════════════════════════════════════════════════════════════
    // Grand Ouest — Pays de la Loire
    // ═══════════════════════════════════════════════════════════════════

    'angers' => [
        'headline_fr' => 'Angers, capitale du végétal et de l\'IoT — Cité de l\'objet connecté, West Data Festival.',
        'headline_en' => 'Angers, capital of plants and IoT — Connected Objects City, West Data Festival.',
        'ecosystem_fr' => 'Angers porte la Cité de l\'objet connecté et organise le West Data Festival, événement de référence sur la data dans le Grand Ouest. L\'écosystème mêle agritech, IoT, supply chain végétale et solutions data pour les filières agricoles.',
        'ecosystem_en' => 'Angers runs the Connected Objects City and organises the West Data Festival, the western France reference event on data. The ecosystem mixes agritech, IoT, plant supply chain and data solutions for agricultural sectors.',
        'sectors' => ['Agritech', 'IoT', 'Data engineering', 'Supply chain végétale'],
    ],

    'le-mans' => [
        'headline_fr' => 'Le Mans, cœur historique de l\'automobile française — entre 24 Heures et mobility tech.',
        'headline_en' => 'Le Mans, historic heart of French automotive — between 24 Hours race and mobility tech.',
        'ecosystem_fr' => 'Le Mans s\'appuie sur l\'industrie automobile, l\'assurance (siège MMA) et un écosystème naissant de mobility tech autour de l\'Université du Mans. La demande locale en logiciel touche les outils métier, les ERP industriels et les sites institutionnels d\'envergure.',
        'ecosystem_en' => 'Le Mans relies on automotive industry, insurance (MMA headquarters) and an emerging mobility tech ecosystem around Le Mans University. Local software demand covers business tools, industrial ERPs and large institutional websites.',
        'sectors' => ['Mobility tech', 'Assurance', 'ERP industriel', 'Sites institutionnels'],
    ],

    // ═══════════════════════════════════════════════════════════════════
    // Paris
    // ═══════════════════════════════════════════════════════════════════

    'paris' => [
        'headline_fr' => 'Paris, premier hub tech d\'Europe continentale — Station F, French Tech Central, écosystème complet.',
        'headline_en' => 'Paris, leading tech hub of continental Europe — Station F, French Tech Central, full ecosystem.',
        'ecosystem_fr' => 'Paris concentre Station F (le plus grand campus de startups au monde), un tissu fintech mature, des éditeurs SaaS B2B, l\'AdTech française et une scène IA de premier plan (Mistral, H, Hugging Face, écosystème PSL/Polytechnique). La demande tech parisienne est exigeante, internationale, et orientée scale.',
        'ecosystem_en' => 'Paris concentrates Station F (the world\'s largest startup campus), a mature fintech fabric, B2B SaaS publishers, France\'s AdTech scene and a leading AI ecosystem (Mistral, H, Hugging Face, PSL/Polytechnique). Parisian tech demand is exacting, international and scale-oriented.',
        'sectors' => ['Fintech', 'SaaS B2B', 'AdTech', 'IA / LLM', 'Marketplaces'],
    ],

    // ═══════════════════════════════════════════════════════════════════
    // Sud-Ouest
    // ═══════════════════════════════════════════════════════════════════

    'bordeaux' => [
        'headline_fr' => 'Bordeaux, place forte du retail tech et de la vinitech — French Tech Bordeaux, écosystème SaaS dense.',
        'headline_en' => 'Bordeaux, retail tech and wine tech stronghold — French Tech Bordeaux, dense SaaS ecosystem.',
        'ecosystem_fr' => 'Bordeaux porte une scène e-commerce mature (Cdiscount historique), une vinitech reconnue mondialement, et un écosystème SaaS B2B en pleine expansion. La ville bénéficie de l\'École d\'ingénieurs ENSEIRB-Matmeca et de la dynamique French Tech Bordeaux.',
        'ecosystem_en' => 'Bordeaux runs a mature e-commerce scene (Cdiscount\'s historical home), a globally recognised wine tech sector and a fast-growing B2B SaaS ecosystem. The city benefits from ENSEIRB-Matmeca engineering school and the French Tech Bordeaux dynamic.',
        'sectors' => ['Retail tech', 'Vinitech', 'SaaS B2B', 'E-commerce'],
    ],

    'toulouse' => [
        'headline_fr' => 'Toulouse, capitale européenne de l\'aérospatial — Aerospace Valley, IA embarquée, deep tech.',
        'headline_en' => 'Toulouse, European aerospace capital — Aerospace Valley, embedded AI, deep tech.',
        'ecosystem_fr' => 'Toulouse réunit Airbus, Thales Alenia Space, le CNES et l\'écosystème Aerospace Valley — la plus grande concentration européenne de compétences spatiales. La ville accueille une scène deep tech en IA embarquée, capteurs, calcul scientifique et logiciels critiques.',
        'ecosystem_en' => 'Toulouse brings together Airbus, Thales Alenia Space, CNES and the Aerospace Valley ecosystem — Europe\'s largest concentration of space expertise. The city hosts a deep tech scene in embedded AI, sensors, scientific computing and mission-critical software.',
        'sectors' => ['Aérospatial', 'IA embarquée', 'Deep tech', 'Logiciels critiques'],
    ],

    'bayonne' => [
        'headline_fr' => 'Bayonne, porte d\'entrée du Pays Basque — Bask Tech, écosystème transfrontalier France-Espagne.',
        'headline_en' => 'Bayonne, gateway to the Basque Country — Bask Tech, French-Spanish cross-border ecosystem.',
        'ecosystem_fr' => 'Bayonne porte avec Biarritz et Anglet la French Tech Pays Basque, et tire parti d\'un écosystème transfrontalier (San Sebastián, Bilbao). Les sujets dominants : tourisme expérientiel, agroalimentaire local, surf tech et lifestyle premium.',
        'ecosystem_en' => 'Bayonne, alongside Biarritz and Anglet, drives French Tech Basque Country and leverages a cross-border ecosystem (San Sebastián, Bilbao). Dominant themes: experiential tourism, local food industry, surf tech and premium lifestyle.',
        'sectors' => ['Tourisme expérientiel', 'Lifestyle premium', 'Agroalimentaire', 'Sites multilingues'],
    ],

    'biarritz' => [
        'headline_fr' => 'Biarritz, scène lifestyle premium — surf tech, tourisme haut de gamme, scène événementielle.',
        'headline_en' => 'Biarritz, premium lifestyle scene — surf tech, high-end tourism, event ecosystem.',
        'ecosystem_fr' => 'Biarritz combine une scène lifestyle premium (surf, gastronomie, hôtellerie), un écosystème événementiel international, et une scène tech à taille humaine très qualitative. Les projets locaux portent sur les marques DTC, le tourisme premium, et les plateformes communautaires sport-outdoor.',
        'ecosystem_en' => 'Biarritz combines a premium lifestyle scene (surf, gastronomy, hospitality), an international events ecosystem and a small but high-quality tech scene. Local projects focus on DTC brands, premium tourism and outdoor-sport community platforms.',
        'sectors' => ['Lifestyle premium', 'Tourisme haut de gamme', 'Surf tech', 'Marques DTC'],
    ],

    // ═══════════════════════════════════════════════════════════════════
    // Frontalier Haute-Savoie
    // ═══════════════════════════════════════════════════════════════════

    'annecy' => [
        'headline_fr' => 'Annecy, hub tech alpin — industrie de précision, outdoor tech, voisinage genevois.',
        'headline_en' => 'Annecy, Alpine tech hub — precision industry, outdoor tech, Geneva neighbourhood.',
        'ecosystem_fr' => 'Annecy bénéficie d\'un tissu industriel de précision unique en France (décolletage, usinage haute précision) et d\'une scène tech moderne autour de l\'industrie 4.0 et de l\'outdoor tech. La proximité avec Genève crée un flux d\'expertise franco-suisse rare en France.',
        'ecosystem_en' => 'Annecy benefits from a precision industry fabric unique in France (machining, high-precision manufacturing) and a modern tech scene focused on industry 4.0 and outdoor tech. Proximity to Geneva creates a French-Swiss expertise flow rare elsewhere in France.',
        'sectors' => ['Industrie 4.0', 'Outdoor tech', 'IoT industriel', 'Logiciels métier'],
    ],

    'annemasse' => [
        'headline_fr' => 'Annemasse, partie française du Grand Genève — agglomération transfrontalière, économie franco-suisse.',
        'headline_en' => 'Annemasse, French side of Greater Geneva — cross-border metro area, French-Swiss economy.',
        'ecosystem_fr' => 'Annemasse fait partie intégrante de l\'agglomération du Grand Genève, traversée chaque jour par plus de 100 000 frontaliers. L\'écosystème local est tiré par la demande suisse — logiciels métier, plateformes de gestion frontalière, services aux PME franco-suisses.',
        'ecosystem_en' => 'Annemasse is an integral part of the Greater Geneva metropolitan area, crossed daily by over 100,000 cross-border workers. The local ecosystem is driven by Swiss demand — business software, cross-border management platforms, French-Swiss SME services.',
        'sectors' => ['Logiciels métier', 'Gestion frontalière', 'Services franco-suisses', 'Industrie'],
    ],

    'thonon-les-bains' => [
        'headline_fr' => 'Thonon-les-Bains, perle du Léman — tourisme premium, eau minérale, services frontaliers.',
        'headline_en' => 'Thonon-les-Bains, pearl of Lake Geneva — premium tourism, mineral water, cross-border services.',
        'ecosystem_fr' => 'Thonon combine un patrimoine touristique d\'exception sur les rives du Léman, l\'industrie de l\'eau minérale (Évian voisin), et une économie frontalière dynamique avec la Suisse. Les besoins tech locaux portent sur les sites multilingues, la réservation et les outils métier industriels.',
        'ecosystem_en' => 'Thonon combines exceptional tourism heritage on Lake Geneva, the mineral water industry (neighbouring Évian) and a dynamic cross-border economy with Switzerland. Local tech needs cover multilingual websites, booking platforms and industrial business tools.',
        'sectors' => ['Tourisme premium', 'Industrie agroalimentaire', 'Services frontaliers', 'Sites multilingues'],
    ],

    // ═══════════════════════════════════════════════════════════════════
    // Luxembourg
    // ═══════════════════════════════════════════════════════════════════

    'luxembourg' => [
        'headline_fr' => 'Luxembourg-Ville, capitale fintech et RegTech d\'Europe — place financière, écosystème ESG, conformité.',
        'headline_en' => 'Luxembourg City, Europe\'s fintech and RegTech capital — financial centre, ESG ecosystem, compliance.',
        'ecosystem_fr' => 'Luxembourg-Ville porte la deuxième place financière d\'Europe en gestion d\'actifs, un écosystème RegTech et compliance de premier plan, et une scène fintech tirée par le LHoFT. Les projets locaux exigent rigueur juridique, multilinguisme (FR/EN/DE) et conformité bancaire stricte.',
        'ecosystem_en' => 'Luxembourg City runs Europe\'s second-largest financial centre for asset management, a leading RegTech and compliance ecosystem and a fintech scene driven by LHoFT. Local projects demand legal rigour, multilingualism (FR/EN/DE) and strict banking compliance.',
        'sectors' => ['Fintech', 'RegTech & Compliance', 'Asset management', 'ESG'],
    ],

    // ═══════════════════════════════════════════════════════════════════
    // Suisse romande
    // ═══════════════════════════════════════════════════════════════════

    'geneve' => [
        'headline_fr' => 'Genève, ville monde — finance privée, organisations internationales, biotech, horlogerie digitale.',
        'headline_en' => 'Geneva, global city — private banking, international organisations, biotech, digital watchmaking.',
        'ecosystem_fr' => 'Genève concentre la banque privée mondiale, les organisations internationales (ONU, OMS, OMC, CICR), un écosystème biotech mature (campus Biotech), et l\'horlogerie de luxe en pleine transformation digitale. La demande tech genevoise est exigeante en sécurité, confidentialité et multilinguisme.',
        'ecosystem_en' => 'Geneva concentrates global private banking, international organisations (UN, WHO, WTO, ICRC), a mature biotech ecosystem (Campus Biotech) and luxury watchmaking undergoing digital transformation. Genevan tech demand is exacting on security, confidentiality and multilingualism.',
        'sectors' => ['Finance privée', 'Biotech', 'Organisations internationales', 'Horlogerie digitale'],
    ],

    'lausanne' => [
        'headline_fr' => 'Lausanne, capitale académique de la Suisse romande — EPFL, MedTech, EdTech, scène deep tech.',
        'headline_en' => 'Lausanne, academic capital of French-speaking Switzerland — EPFL, MedTech, EdTech, deep tech scene.',
        'ecosystem_fr' => 'Lausanne s\'appuie sur l\'EPFL, l\'une des meilleures universités techniques au monde, et son Innovation Park. L\'écosystème mêle MedTech, EdTech (l\'EPFL est leader mondial des MOOC francophones), biotech et deep tech, avec une forte densité de spin-offs académiques.',
        'ecosystem_en' => 'Lausanne relies on EPFL, one of the world\'s top technical universities, and its Innovation Park. The ecosystem mixes MedTech, EdTech (EPFL leads French-speaking MOOCs globally), biotech and deep tech, with a strong density of academic spin-offs.',
        'sectors' => ['MedTech', 'EdTech', 'Biotech', 'Deep tech académique'],
    ],

];
