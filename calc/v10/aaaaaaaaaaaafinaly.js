/**
 * CABLE CALCULATOR - Final Version
 * By Ehsan Morovati
 */

const CABLE_CALCULATOR = {
    // بخش اول: ثوابت و محاسبات پایه
    CONSTANTS: {
        MATERIAL: {
            COPPER: {
                RESISTIVITY: 0.017800,    // Ω.mm²/m at 20°C
                ALPHA: 0.003930,          // Temperature coefficient
                MIN_SECTION: 1.5,         // mm²
                MAX_SECTION_SINGLE: 4,    // Max section for single phase
                CODE: 'N',               // Cable code for copper
                CURRENT_CAPACITY: {       // Current capacity for different sections
                    '1.5': { air: 19, ground: 26 },
                    '2.5': { air: 25, ground: 35 },
                    '4': { air: 34, ground: 46 },
                    '6': { air: 43, ground: 58 },
                    '10': { air: 60, ground: 77 },
                    '16': { air: 80, ground: 100 },
                    '25': { air: 106, ground: 128 },
                    '35': { air: 131, ground: 157 },
                    '50': { air: 159, ground: 185 },
                    '70': { air: 202, ground: 230 },
                    '95': { air: 244, ground: 275 },
                    '120': { air: 282, ground: 315 },
                    '150': { air: 324, ground: 355 },
                    '185': { air: 371, ground: 400 },
                    '240': { air: 436, ground: 465 },
                    '300': { air: 500, ground: 525 },
                    '400': { air: 580, ground: 605 }
                }
            },
            ALUMINUM: {
                RESISTIVITY: 0.028000,    // Ω.mm²/m at 20°C
                ALPHA: 0.004030,          // Temperature coefficient
                MIN_SECTION: 2.5,         // mm²
                CODE: 'NA',              // Cable code for aluminum
                CURRENT_CAPACITY: {       // Current capacity for different sections
                    '2.5': { air: 20, ground: 28 },
                    '4': { air: 27, ground: 37 },
                    '6': { air: 34, ground: 46 },
                    '10': { air: 47, ground: 61 },
                    '16': { air: 63, ground: 79 },
                    '25': { air: 84, ground: 101 },
                    '35': { air: 103, ground: 122 },
                    '50': { air: 125, ground: 144 },
                    '70': { air: 160, ground: 178 },
                    '95': { air: 193, ground: 211 },
                    '120': { air: 224, ground: 240 },
                    '150': { air: 258, ground: 271 },
                    '185': { air: 297, ground: 310 },
                    '240': { air: 350, ground: 361 },
                    '300': { air: 404, ground: 412 },
                    '400': { air: 471, ground: 476 },
                    '500': { air: 545, ground: 545 }
                }
            }
        },

        VOLTAGE: {
            SINGLE_PHASE: 220,
            THREE_PHASE: 380
        },

        STANDARD_SECTIONS: {
            COPPER: [1.5, 2.5, 4, 6, 10, 16, 25, 35, 50, 70, 95, 120, 150, 185, 240, 300, 400],
            ALUMINUM: [2.5, 4, 6, 10, 16, 25, 35, 50, 70, 95, 120, 150, 185, 240, 300, 400, 500]
        },

        INSTALLATION: {
            METHODS: {
                AIR: 'air',
                WALL: 'wall',
                NATURAL_GROUND: 'natural_ground',
                WET_GROUND: 'wet_ground'
            },
            GROUND_METHODS: ['natural_ground', 'wet_ground'],
            AIR_METHODS: ['air', 'wall'],
            SHIELD_COMPATIBLE: ['air', 'wall']
        },

        POWER: {
            MAX_SINGLE_PHASE: 7,         // kW
            WARNING_THRESHOLD: 6.5,      // kW
            SAFETY_FACTOR: 1.25          // 25% safety margin
        },

        TEMPERATURE: {
            REFERENCE: 20,               // °C
            VALID_VALUES: [20, 30, 40, 50, 60],
            MAX: 60,
            MIN: 20
        }
    },

    // بخش دوم: ضرایب تصحیح
    CORRECTION_FACTORS: {
        INSTALLATION: {
            air: 1.100,
            wall: 1.000,
            natural_ground: 0.900,
            wet_ground: 1.000
        },

        CONFIGURATION: {
            multicore: 0.900,
            'single-core': 1.000
        },

        ARRANGEMENT: {
            flat: {
                '1D': 0.850,
                '2D': 0.900
            },
            triangular: {
                '1D': 0.900,
                '2D': 1.000
            }
        },

        INSULATION: {
            XLPE: {
                20: 1.000,
                30: 0.970,
                40: 0.940,
                50: 0.900,
                60: 0.850
            },
            PVC: {
                20: 1.000,
                30: 0.910,
                40: 0.820,
                50: 0.730,
                60: 0.580
            }
        }
    },

     PRODUCT_CATEGORIES: {
        BY_TYPE: {
            'ARMOURED': {
                id: 'armoured-cable',
                title: 'Armoured Cable - SWA & AWA',
                url: '/electrical-cable-and-accessories/cables-by-type/armoured-cable',
                description: 'کابل‌های زره‌دار با سیم فولادی و آلومینیومی',
                cables: ['NYY', 'NYCY', 'N2XY', 'N2XCY', 'N2XRY'],
                sections: [1.5, 2.5, 4, 6, 10, 16, 25, 35, 50, 70, 95, 120, 150, 185, 240, 300, 400],
                environments: ['ground', 'air', 'wall']
            },
            'CONTROL': {
                id: 'control-cable',
                title: 'Control Cable',
                url: '/electrical-cable-and-accessories/cables-by-type/control-cable',
                description: 'کابل‌های کنترل و ابزار دقیق',
                cables: ['YY', 'CY', 'SY'],
                sections: [1.5, 2.5, 4],
                environments: ['air', 'wall']
            },
            'RUBBER_FLEXIBLE': {
                id: 'rubber-flexible-cable',
                title: 'Rubber Flexible Cable',
                url: '/electrical-cable-and-accessories/cables-by-type/rubber-flexible-cable',
                description: 'کابل‌های انعطاف‌پذیر لاستیکی با قابلیت خمش بالا',
                cables: ['FLEXIBLE'],
                sections: [1.5, 2.5, 4, 6],
                environments: ['air']
            },
            'MINING': {
                id: 'mining-cable',
                title: 'Mining, Drilling & Tunnelling Cable',
                url: '/electrical-cable-and-accessories/cables-by-type/mining-drilling-tunnelling-cable',
                description: 'کابل‌های مخصوص معدن و حفاری با مقاومت مکانیکی بالا',
                cables: ['N2XRY', 'NYRY'],
                sections: [25, 35, 50, 70, 95, 120, 150, 185, 240],
                environments: ['ground']
            },
            'MARINE': {
                id: 'marine-cable',
                title: 'Marine & Offshore Cable',
                url: '/electrical-cable-and-accessories/cables-by-type/marine-and-offshore-cable',
                description: 'کابل‌های دریایی مقاوم در برابر شرایط محیطی سخت',
                cables: ['N2XY', 'NYCY'],
                sections: [1.5, 2.5, 4, 6, 10, 16, 25, 35, 50],
                environments: ['air', 'wall']
            },
            'INDUSTRIAL': {
                id: 'industrial-cable',
                title: 'Industrial Cable',
                url: '/electrical-cable-and-accessories/cables-by-type/industrial-cable',
                description: 'کابل‌های صنعتی با قابلیت اطمینان بالا',
                cables: ['N2XY', 'NYCY', 'N2XRY'],
                sections: [35, 50, 70, 95, 120, 150, 185, 240, 300, 400],
                environments: ['air', 'wall', 'ground']
            },
            'POWER_DISTRIBUTION': {
                id: 'power-distribution',
                title: 'Power Distribution Cable',
                url: '/electrical-cable-and-accessories/cables-by-type/power-distribution-cable',
                description: 'کابل‌های توزیع برق فشار ضعیف و متوسط',
                cables: ['N2XY', 'N2XRY', 'NYY'],
                sections: [95, 120, 150, 185, 240, 300, 400],
                environments: ['ground', 'wall']
            }
        },

        BY_INDUSTRY: {
            'BUILDING': {
                id: 'building-construction',
                title: 'Building & Construction',
                url: '/electrical-cable-and-accessories/cables-by-industry/building-construction',
                description: 'کابل‌های مناسب برای ساختمان‌های مسکونی و تجاری',
                maxSection: 16,
                environments: ['air', 'wall']
            },
            'INDUSTRIAL_AUTOMATION': {
                id: 'industrial-automation',
                title: 'Industrial Automation',
                url: '/electrical-cable-and-accessories/cables-by-industry/industrial-automation',
                description: 'کابل‌های اتوماسیون صنعتی و کنترل',
                minSection: 16,
                environments: ['air', 'wall', 'ground']
            },
            'INFRASTRUCTURE': {
                id: 'infrastructure',
                title: 'Infrastructure & Utilities',
                url: '/electrical-cable-and-accessories/cables-by-industry/infrastructure',
                description: 'کابل‌های مناسب برای زیرساخت‌های شهری و صنعتی',
                minSection: 50,
                environments: ['ground']
            }
        }
    },

    // بخش چهارم: توابع اصلی محاسبات
    validateInputs(data) {
        const requiredFields = [
            'phase', 'power', 'conductor', 'length', 'temperature',
            'powerFactor', 'voltageDrop', 'installMethod', 'configuration',
            'arrangement', 'cableDistance', 'insulation', 'shield'
        ];

        for (const field of requiredFields) {
            if (!data[field]) {
                throw new Error(`فیلد ${field} الزامی است`);
            }
        }

        if (data.power <= 0) {
            throw new Error('توان باید بزرگتر از صفر باشد');
        }

        if (data.length <= 0) {
            throw new Error('طول کابل باید بزرگتر از صفر باشد');
        }

        const pf = parseFloat(data.powerFactor);
        if (pf <= 0 || pf > 1) {
            throw new Error('ضریب توان باید بین 0 و 1 باشد');
        }

        const vd = parseFloat(data.voltageDrop);
        if (vd <= 0 || vd > 10) {
            throw new Error('افت ولتاژ باید بین 0 و 10 درصد باشد');
        }

        this.validatePhaseAndPower(data.phase, data.power);
        this.validateTemperature(data.temperature);
        this.validateInstallationAndInsulation(data.installMethod, data.insulation);
        this.validateShieldRequirements(data.shield, data.installMethod, data.conductor, data.insulation);
    },

    validatePhaseAndPower(phase, power) {
        if (phase === 'single_phase' && power > this.CONSTANTS.POWER.MAX_SINGLE_PHASE) {
            throw new Error('برای توان بالای 7 کیلووات باید از سه فاز استفاده کنید');
        }
    },

    validateTemperature(temp) {
        if (!this.CONSTANTS.TEMPERATURE.VALID_VALUES.includes(parseInt(temp))) {
            throw new Error('دمای انتخاب شده نامعتبر است');
        }
    },

    validateInstallationAndInsulation(installMethod, insulation) {
        if (this.CONSTANTS.INSTALLATION.GROUND_METHODS.includes(installMethod)) {
            if (insulation !== 'XLPE') {
                throw new Error('برای نصب در خاک فقط عایق XLPE مجاز است');
            }
        }
    },

    validateShieldRequirements(shield, installMethod, conductor, insulation) {
        if (shield === 'with_shield') {
            if (!this.CONSTANTS.INSTALLATION.SHIELD_COMPATIBLE.includes(installMethod)) {
                throw new Error('شیلد فقط برای نصب هوایی یا روی دیوار مجاز است');
            }
            if (conductor === 'al' && insulation !== 'XLPE') {
                throw new Error('برای کابل‌های آلومینیومی شیلددار، فقط عایق XLPE مجاز است');
            }
        }
    },

    calculateEffectiveLength(length, phase) {
        return phase === 'single_phase' ? length * 2 : length;
    },

    getSystemVoltage(phase) {
        return phase === 'single_phase' ? 
            this.CONSTANTS.VOLTAGE.SINGLE_PHASE : 
            this.CONSTANTS.VOLTAGE.THREE_PHASE;
    },

    calculateVoltageDrop(dropPercentage, voltage) {
        return (parseFloat(dropPercentage) / 100) * voltage;
    },

    getMaterialProperties(conductor) {
        return conductor === 'cu' ? 
            this.CONSTANTS.MATERIAL.COPPER : 
            this.CONSTANTS.MATERIAL.ALUMINUM;
    },

    calculateTemperatureFactor(temp, material) {
        return 1 + (material.ALPHA * (this.CONSTANTS.TEMPERATURE.REFERENCE - temp));
    },

calculateNominalCurrent(power, voltage, powerFactor, phase) {
        const powerWatts = power * 1000;
        return phase === 'single_phase' ?
            powerWatts / (voltage * powerFactor) :
            powerWatts / (Math.sqrt(3) * voltage * powerFactor);
    },

    calculateCorrectionFactors(data) {
        const temp = parseInt(data.temperature);
        const material = this.getMaterialProperties(data.conductor);
        
        const kt = this.calculateTemperatureFactor(temp, material);
        const kc = this.CORRECTION_FACTORS.INSTALLATION[data.installMethod];
        const km = this.CORRECTION_FACTORS.CONFIGURATION[data.configuration];
        const ki = this.CORRECTION_FACTORS.ARRANGEMENT[data.arrangement][data.cableDistance];
        const kins = this.CORRECTION_FACTORS.INSULATION[data.insulation][temp];

        if (!kt || !kc || !km || !ki || !kins) {
            throw new Error('خطا در محاسبه ضرایب تصحیح');
        }

        return {
            kt: parseFloat(kt.toFixed(4)),
            kc: parseFloat(kc.toFixed(3)),
            km: parseFloat(km.toFixed(3)),
            ki: parseFloat(ki.toFixed(3)),
            kins: parseFloat(kins.toFixed(3))
        };
    },

    calculateCrossSection(data) {
        const powerWatts = data.power * 1000;
        const length = this.calculateEffectiveLength(data.length, data.phase);
        const voltage = this.getSystemVoltage(data.phase);
        const deltaV = this.calculateVoltageDrop(data.voltageDrop, voltage);
        const material = this.getMaterialProperties(data.conductor);
        const factors = this.calculateCorrectionFactors(data);
        
        const { kt, kc, km, ki, kins } = factors;
        const numerator = powerWatts * length * material.RESISTIVITY * 2;
        
        const denominator = data.phase === 'single_phase' ?
            (deltaV * voltage * data.powerFactor * kt * km * kc * ki * kins) :
            (Math.sqrt(3) * deltaV * voltage * data.powerFactor * kt * km * kc * ki * kins);

        return {
            calculatedSection: numerator / denominator,
            factors: factors,
            numerator: parseFloat(numerator.toFixed(2)),
            denominator: parseFloat(denominator.toFixed(2))
        };
    },

    calculateCurrentCapacity(section, installMethod, material, insulation) {
        const isGround = this.CONSTANTS.INSTALLATION.GROUND_METHODS.includes(installMethod);
        const environment = isGround ? 'ground' : 'air';
        
        const baseCapacity = material.CURRENT_CAPACITY[section.toString()][environment];
        if (!baseCapacity) {
            throw new Error('خطا در محاسبه ظرفیت جریان');
        }

        const insulationFactor = insulation === 'XLPE' ? 1.15 : 1.0;
        return baseCapacity * insulationFactor;
    },

    determineStandardSection(calculatedSection, conductor, nominalCurrent, installMethod, insulation, phase) {
        const material = this.getMaterialProperties(conductor);
        const sections = this.CONSTANTS.STANDARD_SECTIONS[
            conductor === 'cu' ? 'COPPER' : 'ALUMINUM'
        ];
        
        if (conductor === 'al' && phase === 'single_phase') {
            return 6;  // استاندارد IEC برای آلومینیوم تکفاز
        }

        const requiredCurrent = nominalCurrent * this.CONSTANTS.POWER.SAFETY_FACTOR;

        let standardSection = sections.find(section => {
            const currentCapacity = this.calculateCurrentCapacity(
                section,
                installMethod,
                material,
                insulation
            );
            return currentCapacity >= requiredCurrent;
        });

        const voltageDropSection = sections.find(section => section >= calculatedSection);
        if (voltageDropSection > standardSection) {
            standardSection = voltageDropSection;
        }

        if (!standardSection) {
            throw new Error('سطح مقطع محاسبه شده خارج از محدوده مجاز است');
        }

        return standardSection;
    },

    determineCableType(data) {
        if (data.phase === 'single_phase' && 
            data.conductor === 'cu' && 
            !this.CONSTANTS.INSTALLATION.GROUND_METHODS.includes(data.installMethod)) {
            return 'FLEXIBLE CABLE';
        }

        let cableCode = data.conductor === 'cu' ? 
            this.CONSTANTS.MATERIAL.COPPER.CODE : 
            this.CONSTANTS.MATERIAL.ALUMINUM.CODE;

        cableCode += data.insulation === 'XLPE' ? '2X' : 'Y';

        if (data.shield === 'with_shield') {
            cableCode += 'C';
        }

        if (this.CONSTANTS.INSTALLATION.GROUND_METHODS.includes(data.installMethod) && 
            data.insulation === 'XLPE') {
            cableCode += 'R';
        }

        cableCode += 'Y';

        return cableCode;
    },

    checkWarnings(data, standardSection, nominalCurrent) {
        const warnings = [];

        if (data.phase === 'single_phase') {
            if (data.power >= this.CONSTANTS.POWER.WARNING_THRESHOLD) {
                warnings.push('هشدار: توان نزدیک به حد مجاز تک فاز است');
            }

            const material = this.getMaterialProperties(data.conductor);
            if (standardSection > material.MAX_SECTION_SINGLE) {
                warnings.push('توصیه می‌شود از سیستم سه فاز استفاده کنید');
            }
        }

        const currentCapacity = this.calculateCurrentCapacity(
            standardSection,
            data.installMethod,
            this.getMaterialProperties(data.conductor),
            data.insulation
        );

        if (nominalCurrent * this.CONSTANTS.POWER.SAFETY_FACTOR > currentCapacity * 0.9) {
            warnings.push('هشدار: جریان نامی نزدیک به ظرفیت حداکثر کابل است');
        }

        if (parseInt(data.temperature) >= 50) {
            warnings.push('هشدار: دمای کارکرد بالا است');
        }

        return warnings;
    },

    findSuitableProducts(calculationResults) {
        const results = [];
        const { 
            cableType, 
            standardSection, 
            insulation,
            installMethod,
            phase,
            conductor,
            shield
        } = calculationResults;

        const environment = this.CONSTANTS.INSTALLATION.GROUND_METHODS.includes(installMethod) 
            ? 'ground' 
            : installMethod === 'wall' ? 'wall' : 'air';

        // بررسی محصولات بر اساس نوع کابل
        Object.values(this.PRODUCT_CATEGORIES.BY_TYPE).forEach(category => {
            // بررسی تطابق نوع کابل
            const matchesCableType = category.cables.some(cable => 
                cableType.includes(cable) || 
                (cableType === 'FLEXIBLE CABLE' && cable === 'FLEXIBLE')
            );

            // بررسی تطابق سطح مقطع
            const matchesSection = category.sections.includes(standardSection);

            // بررسی تطابق محیط نصب
            const matchesEnvironment = category.environments.includes(environment);

            if (matchesCableType && matchesSection && matchesEnvironment) {
                results.push({
                    category: category.id,
                    title: category.title,
                    url: category.url,
                    description: category.description,
                    specifications: {
                        cableType,
                        section: standardSection,
                        insulation,
                        shield,
                        phase: phase === 'single_phase' ? 'تک فاز' : 'سه فاز',
                        conductor: conductor === 'cu' ? 'مس' : 'آلومینیوم'
                    }
                });
            }
        });

        // بررسی محصولات بر اساس صنعت
        Object.values(this.PRODUCT_CATEGORIES.BY_INDUSTRY).forEach(category => {
            const matchesSectionRange = 
                (!category.minSection || standardSection >= category.minSection) &&
                (!category.maxSection || standardSection <= category.maxSection);

            const matchesEnvironment = category.environments.includes(environment);

            if (matchesSectionRange && matchesEnvironment) {
                results.push({
                    category: category.id,
                    title: category.title,
                    url: category.url,
                    description: category.description,
                    specifications: {
                        cableType,
                        section: standardSection,
                        insulation,
                        shield,
                        phase: phase === 'single_phase' ? 'تک فاز' : 'سه فاز',
                        conductor: conductor === 'cu' ? 'مس' : 'آلومینیوم'
                    }
                });
            }
        });

        // فیلتر کردن محصولات تکراری و مرتب‌سازی
        const uniqueResults = Array.from(new Set(results.map(r => r.category)))
            .map(category => results.find(r => r.category === category))
            .sort((a, b) => {
                // اولویت‌بندی نتایج
                const typeOrder = ['ARMOURED', 'POWER_DISTRIBUTION', 'INDUSTRIAL', 'RUBBER_FLEXIBLE'];
                const aIndex = typeOrder.indexOf(a.category);
                const bIndex = typeOrder.indexOf(b.category);
                return (aIndex === -1 ? 999 : aIndex) - (bIndex === -1 ? 999 : bIndex);
            });

        return uniqueResults;
    },

    // متد اصلی محاسبه - بدون تغییر
    calculate(data) {
        try {
            this.validateInputs(data);

            const crossSectionResult = this.calculateCrossSection(data);
            const nominalCurrent = this.calculateNominalCurrent(
                data.power,
                this.getSystemVoltage(data.phase),
                data.powerFactor,
                data.phase
            );

            const standardSection = this.determineStandardSection(
                crossSectionResult.calculatedSection,
                data.conductor,
                nominalCurrent,
                data.installMethod,
                data.insulation,
                data.phase
            );

            const cableType = this.determineCableType(data);
            const warnings = this.checkWarnings(data, standardSection, nominalCurrent);

            const results = {
                calculatedSection: parseFloat(crossSectionResult.calculatedSection.toFixed(3)),
                standardSection: standardSection,
                nominalCurrent: parseFloat(nominalCurrent.toFixed(2)),
                cableType: cableType,
                factors: crossSectionResult.factors,
                warnings: warnings,
                details: {
                    numerator: crossSectionResult.numerator,
                    denominator: crossSectionResult.denominator,
                    effectiveLength: this.calculateEffectiveLength(data.length, data.phase),
                    voltageDrop: this.calculateVoltageDrop(
                        data.voltageDrop, 
                        this.getSystemVoltage(data.phase)
                    ),
                    currentCapacity: this.calculateCurrentCapacity(
                        standardSection,
                        data.installMethod,
                        this.getMaterialProperties(data.conductor),
                        data.insulation
                    )
                }
            };

            // اضافه کردن محصولات پیشنهادی بهبود یافته
            results.products = this.findSuitableProducts({
                cableType,
                standardSection,
                insulation: data.insulation,
                installMethod: data.installMethod,
                phase: data.phase,
                conductor: data.conductor,
                shield: data.shield
            });

            return results;

        } catch (error) {
            throw error;
        }
    }
};

// Export
if (typeof window !== 'undefined') {
    window.CABLE_CALCULATOR = CABLE_CALCULATOR;
} else if (typeof module !== 'undefined' && module.exports) {
    module.exports = CABLE_CALCULATOR;
}