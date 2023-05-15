<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use PhpCsFixer\Fixer\Alias\EregToPregFixer;
use PhpCsFixer\Fixer\Alias\NoAliasFunctionsFixer;
use PhpCsFixer\Fixer\Alias\NoMixedEchoPrintFixer;
use PhpCsFixer\Fixer\Alias\PowToExponentiationFixer;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoMultilineWhitespaceAroundDoubleArrowFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoTrailingCommaInSinglelineArrayFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoWhitespaceBeforeCommaInArrayFixer;
use PhpCsFixer\Fixer\ArrayNotation\NormalizeIndexBraceFixer;
use PhpCsFixer\Fixer\ControlStructure\TrailingCommaInMultilineFixer;
use PhpCsFixer\Fixer\ArrayNotation\TrimArraySpacesFixer;
use PhpCsFixer\Fixer\ArrayNotation\WhitespaceAfterCommaInArrayFixer;
use PhpCsFixer\Fixer\Basic\BracesFixer;
use PhpCsFixer\Fixer\Basic\EncodingFixer;
use PhpCsFixer\Fixer\Basic\NonPrintableCharacterFixer;
use PhpCsFixer\Fixer\Casing\ConstantCaseFixer;
use PhpCsFixer\Fixer\Casing\LowercaseKeywordsFixer;
use PhpCsFixer\Fixer\Casing\LowercaseStaticReferenceFixer;
use PhpCsFixer\Fixer\Casing\MagicConstantCasingFixer;
use PhpCsFixer\Fixer\Casing\NativeFunctionCasingFixer;
use PhpCsFixer\Fixer\CastNotation\CastSpacesFixer;
use PhpCsFixer\Fixer\CastNotation\LowercaseCastFixer;
use PhpCsFixer\Fixer\CastNotation\ModernizeTypesCastingFixer;
use PhpCsFixer\Fixer\CastNotation\NoShortBoolCastFixer;
use PhpCsFixer\Fixer\CastNotation\ShortScalarCastFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassAttributesSeparationFixer;
use PhpCsFixer\Fixer\ClassNotation\ClassDefinitionFixer;
use PhpCsFixer\Fixer\ClassNotation\NoBlankLinesAfterClassOpeningFixer;
use PhpCsFixer\Fixer\ClassNotation\NoNullPropertyInitializationFixer;
use PhpCsFixer\Fixer\ClassNotation\NoPhp4ConstructorFixer;
use PhpCsFixer\Fixer\ClassNotation\NoUnneededFinalMethodFixer;
use PhpCsFixer\Fixer\ClassNotation\OrderedClassElementsFixer;
use PhpCsFixer\Fixer\ClassNotation\ProtectedToPrivateFixer;
use PhpCsFixer\Fixer\ClassNotation\SelfAccessorFixer;
use PhpCsFixer\Fixer\ClassNotation\SingleClassElementPerStatementFixer;
use PhpCsFixer\Fixer\ClassNotation\VisibilityRequiredFixer;
use PhpCsFixer\Fixer\Comment\NoEmptyCommentFixer;
use PhpCsFixer\Fixer\Comment\NoTrailingWhitespaceInCommentFixer;
use PhpCsFixer\Fixer\Comment\SingleLineCommentStyleFixer;
use PhpCsFixer\Fixer\ConstantNotation\NativeConstantInvocationFixer;
use PhpCsFixer\Fixer\ControlStructure\ElseifFixer;
use PhpCsFixer\Fixer\ControlStructure\IncludeFixer;
use PhpCsFixer\Fixer\ControlStructure\NoBreakCommentFixer;
use PhpCsFixer\Fixer\ControlStructure\NoSuperfluousElseifFixer;
use PhpCsFixer\Fixer\ControlStructure\NoTrailingCommaInListCallFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUnneededControlParenthesesFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUnneededCurlyBracesFixer;
use PhpCsFixer\Fixer\ControlStructure\NoUselessElseFixer;
use PhpCsFixer\Fixer\ControlStructure\SwitchCaseSemicolonToColonFixer;
use PhpCsFixer\Fixer\ControlStructure\SwitchCaseSpaceFixer;
use PhpCsFixer\Fixer\ControlStructure\YodaStyleFixer;
use PhpCsFixer\Fixer\FunctionNotation\FunctionDeclarationFixer;
use PhpCsFixer\Fixer\FunctionNotation\FunctionTypehintSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\MethodArgumentSpaceFixer;
use PhpCsFixer\Fixer\FunctionNotation\NoSpacesAfterFunctionNameFixer;
use PhpCsFixer\Fixer\FunctionNotation\PhpdocToReturnTypeFixer;
use PhpCsFixer\Fixer\FunctionNotation\ReturnTypeDeclarationFixer;
use PhpCsFixer\Fixer\FunctionNotation\VoidReturnFixer;
use PhpCsFixer\Fixer\Import\NoLeadingImportSlashFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\Import\SingleImportPerStatementFixer;
use PhpCsFixer\Fixer\Import\SingleLineAfterImportsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\CombineConsecutiveIssetsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\CombineConsecutiveUnsetsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DeclareEqualNormalizeFixer;
use PhpCsFixer\Fixer\LanguageConstruct\DirConstantFixer;
use PhpCsFixer\Fixer\LanguageConstruct\FunctionToConstantFixer;
use PhpCsFixer\Fixer\LanguageConstruct\IsNullFixer;
use PhpCsFixer\Fixer\ListNotation\ListSyntaxFixer;
use PhpCsFixer\Fixer\NamespaceNotation\BlankLineAfterNamespaceFixer;
use PhpCsFixer\Fixer\NamespaceNotation\NoLeadingNamespaceWhitespaceFixer;
use PhpCsFixer\Fixer\NamespaceNotation\SingleBlankLineBeforeNamespaceFixer;
use PhpCsFixer\Fixer\Naming\NoHomoglyphNamesFixer;
use PhpCsFixer\Fixer\Operator\BinaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\ConcatSpaceFixer;
use PhpCsFixer\Fixer\Operator\IncrementStyleFixer;
use PhpCsFixer\Fixer\Operator\NewWithBracesFixer;
use PhpCsFixer\Fixer\Operator\ObjectOperatorWithoutWhitespaceFixer;
use PhpCsFixer\Fixer\Operator\StandardizeNotEqualsFixer;
use PhpCsFixer\Fixer\Operator\TernaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\Operator\TernaryToNullCoalescingFixer;
use PhpCsFixer\Fixer\Operator\UnaryOperatorSpacesFixer;
use PhpCsFixer\Fixer\PhpTag\BlankLineAfterOpeningTagFixer;
use PhpCsFixer\Fixer\PhpTag\FullOpeningTagFixer;
use PhpCsFixer\Fixer\PhpTag\NoClosingTagFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitDedicateAssertFixer;
use PhpCsFixer\Fixer\PhpUnit\PhpUnitFqcnAnnotationFixer;
use PhpCsFixer\Fixer\Phpdoc\NoBlankLinesAfterPhpdocFixer;
use PhpCsFixer\Fixer\Phpdoc\NoEmptyPhpdocFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocIndentFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocInlineTagFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocInlineTagNormalizerFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoAccessFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoAliasTagFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoEmptyReturnFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoPackageFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocNoUselessInheritdocFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocReturnSelfReferenceFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocScalarFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSeparationFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocSingleLineVarSpacingFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTrimConsecutiveBlankLineSeparationFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTrimFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTypesFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocTypesOrderFixer;
use PhpCsFixer\Fixer\Phpdoc\PhpdocVarWithoutNameFixer;
use PhpCsFixer\Fixer\ReturnNotation\NoUselessReturnFixer;
use PhpCsFixer\Fixer\ReturnNotation\ReturnAssignmentFixer;
use PhpCsFixer\Fixer\ReturnNotation\SimplifiedNullReturnFixer;
use PhpCsFixer\Fixer\Semicolon\MultilineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\Semicolon\NoEmptyStatementFixer;
use PhpCsFixer\Fixer\Semicolon\NoSinglelineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\Semicolon\SemicolonAfterInstructionFixer;
use PhpCsFixer\Fixer\Semicolon\SpaceAfterSemicolonFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\StringNotation\SingleQuoteFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use PhpCsFixer\Fixer\Whitespace\IndentationTypeFixer;
use PhpCsFixer\Fixer\Whitespace\LineEndingFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use PhpCsFixer\Fixer\Whitespace\NoExtraBlankLinesFixer;
use PhpCsFixer\Fixer\Whitespace\NoSpacesAroundOffsetFixer;
use PhpCsFixer\Fixer\Whitespace\NoSpacesInsideParenthesisFixer;
use PhpCsFixer\Fixer\Whitespace\NoTrailingWhitespaceFixer;
use PhpCsFixer\Fixer\Whitespace\NoWhitespaceInBlankLineFixer;
use PhpCsFixer\Fixer\Whitespace\SingleBlankLineAtEofFixer;
use SlevomatCodingStandard\Sniffs\Commenting\ForbiddenAnnotationsSniff;
use SlevomatCodingStandard\Sniffs\Commenting\InlineDocCommentDeclarationSniff;
use SlevomatCodingStandard\Sniffs\Commenting\RequireOneLinePropertyDocCommentSniff;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/src',
    ]);

    $services = $ecsConfig->services();

    $services->set(EregToPregFixer::class);

    $services->set(NoAliasFunctionsFixer::class);

    $services->set(PowToExponentiationFixer::class);

    $services->set(NoMixedEchoPrintFixer::class)
        ->call('configure', [['use' => 'echo']]);

    $services->set(ArraySyntaxFixer::class)
        ->call('configure', [['syntax' => 'short']]);

    $services->set(NoMultilineWhitespaceAroundDoubleArrowFixer::class);

    $services->set(NormalizeIndexBraceFixer::class);

    $services->set(NoTrailingCommaInSinglelineArrayFixer::class);

    $services->set(NoWhitespaceBeforeCommaInArrayFixer::class);

    $services->set(TrailingCommaInMultilineFixer::class);

    $services->set(TrimArraySpacesFixer::class);

    $services->set(WhitespaceAfterCommaInArrayFixer::class);

    $services->set(BracesFixer::class)
        ->call('configure', [['allow_single_line_closure' => true]]);

    $services->set(EncodingFixer::class);

    $services->set(NonPrintableCharacterFixer::class);

    $services->set(ConstantCaseFixer::class);

    $services->set(LowercaseKeywordsFixer::class);

    $services->set(LowercaseStaticReferenceFixer::class);

    $services->set(MagicConstantCasingFixer::class);

    $services->set(NativeFunctionCasingFixer::class);

    $services->set(CastSpacesFixer::class);

    $services->set(LowercaseCastFixer::class);

    $services->set(ModernizeTypesCastingFixer::class);

    $services->set(NoShortBoolCastFixer::class);

    $services->set(ShortScalarCastFixer::class);

    $services->set(ClassDefinitionFixer::class)
        ->call('configure', [['single_item_single_line' => true, 'multi_line_extends_each_single_line' => true]]);

    $services->set(ClassAttributesSeparationFixer::class)
        ->call('configure', [['elements' => ['method' => 'one']]]);

    $services->set(NoBlankLinesAfterClassOpeningFixer::class);

    $services->set(NoNullPropertyInitializationFixer::class);

    $services->set(NoPhp4ConstructorFixer::class);

    $services->set(NoUnneededFinalMethodFixer::class);

    $services->set(OrderedClassElementsFixer::class)
        ->call('configure', [['order' => ['use_trait', 'constant_public', 'constant_protected', 'constant_private', 'property_public_static', 'property_protected_static', 'property_private_static', 'property_public', 'property_protected', 'property_private', 'construct', 'destruct', 'magic', 'method_public_static', 'method_public', 'method_protected_static', 'method_protected', 'method_private_static', 'method_private']]]);

    $services->set(ProtectedToPrivateFixer::class);

    $services->set(SelfAccessorFixer::class);

    $services->set(SingleClassElementPerStatementFixer::class);

    $services->set(VisibilityRequiredFixer::class)
        ->call('configure', [['elements' => ['const', 'property', 'method']]]);

    $services->set(NoEmptyCommentFixer::class);

    $services->set(NoTrailingWhitespaceInCommentFixer::class);

    $services->set(SingleLineCommentStyleFixer::class)
        ->call('configure', [['comment_types' => ['hash']]]);

    $services->set(ElseifFixer::class);

    $services->set(IncludeFixer::class);

    $services->set(NoBreakCommentFixer::class);

    $services->set(NoSuperfluousElseifFixer::class);

    $services->set(NoTrailingCommaInListCallFixer::class);

    $services->set(NoUnneededControlParenthesesFixer::class);

    $services->set(NoUnneededCurlyBracesFixer::class);

    $services->set(NoUselessElseFixer::class);

    $services->set(SwitchCaseSemicolonToColonFixer::class);

    $services->set(SwitchCaseSpaceFixer::class);

    $services->set(YodaStyleFixer::class)
        ->call('configure', [['always_move_variable' => true]]);

    $services->set(NativeConstantInvocationFixer::class);

    $services->set(FunctionDeclarationFixer::class);

    $services->set(FunctionTypehintSpaceFixer::class);

    $services->set(MethodArgumentSpaceFixer::class)
        ->call('configure', [['on_multiline' => 'ensure_fully_multiline']]);

    $services->set(NoSpacesAfterFunctionNameFixer::class);

    $services->set(PhpdocToReturnTypeFixer::class);

    $services->set(ReturnTypeDeclarationFixer::class);

    $services->set(VoidReturnFixer::class);

    $services->set(NoLeadingImportSlashFixer::class);

    $services->set(NoUnusedImportsFixer::class);

    $services->set(OrderedImportsFixer::class)
        ->call('configure', [['imports_order' => ['const', 'class', 'function']]]);

    $services->set(SingleImportPerStatementFixer::class);

    $services->set(SingleLineAfterImportsFixer::class);

    $services->set(CombineConsecutiveIssetsFixer::class);

    $services->set(CombineConsecutiveUnsetsFixer::class);

    $services->set(DeclareEqualNormalizeFixer::class);

    $services->set(DirConstantFixer::class);

    $services->set(FunctionToConstantFixer::class);

    $services->set(IsNullFixer::class);

    $services->set(ListSyntaxFixer::class)
        ->call('configure', [['syntax' => 'short']]);

    $services->set(BlankLineAfterNamespaceFixer::class);

    $services->set(NoLeadingNamespaceWhitespaceFixer::class);

    $services->set(SingleBlankLineBeforeNamespaceFixer::class);

    $services->set(NoHomoglyphNamesFixer::class);

    $services->set(BinaryOperatorSpacesFixer::class)
        ->call('configure', [['operators' => ['=>' => null, '=' => null]]]);

    $services->set(ConcatSpaceFixer::class)
        ->call('configure', [['spacing' => 'none']]);

    $services->set(NewWithBracesFixer::class);

    $services->set(ObjectOperatorWithoutWhitespaceFixer::class);

    $services->set(IncrementStyleFixer::class);

    $services->set(StandardizeNotEqualsFixer::class);

    $services->set(TernaryOperatorSpacesFixer::class);

    $services->set(TernaryToNullCoalescingFixer::class);

    $services->set(UnaryOperatorSpacesFixer::class);

    $services->set(NoBlankLinesAfterPhpdocFixer::class);

    $services->set(NoEmptyPhpdocFixer::class);

    $services->set(PhpdocIndentFixer::class);

    $services->set(PhpdocInlineTagFixer::class);

    $services->set(PhpdocNoAccessFixer::class);

    $services->set(PhpdocNoAliasTagFixer::class);

    $services->set(PhpdocNoEmptyReturnFixer::class);

    $services->set(PhpdocNoPackageFixer::class);

    $services->set(PhpdocNoUselessInheritdocFixer::class);

    $services->set(PhpdocReturnSelfReferenceFixer::class);

    $services->set(PhpdocScalarFixer::class);

    $services->set(PhpdocSeparationFixer::class);

    $services->set(PhpdocSingleLineVarSpacingFixer::class);

    $services->set(PhpdocTrimConsecutiveBlankLineSeparationFixer::class);

    $services->set(PhpdocTrimFixer::class);

    $services->set(PhpdocTypesFixer::class);

    $services->set(PhpdocTypesOrderFixer::class)
        ->call('configure', [['null_adjustment' => 'always_last', 'sort_algorithm' => 'none']]);

    $services->set(PhpdocVarWithoutNameFixer::class);

    $services->set(BlankLineAfterOpeningTagFixer::class);

    $services->set(FullOpeningTagFixer::class);

    $services->set(NoClosingTagFixer::class);

    $services->set(PhpUnitDedicateAssertFixer::class);

    $services->set(PhpUnitFqcnAnnotationFixer::class);

    $services->set(NoUselessReturnFixer::class);

    $services->set(ReturnAssignmentFixer::class);

    $services->set(SimplifiedNullReturnFixer::class);

    $services->set(NoEmptyStatementFixer::class);

    $services->set(NoSinglelineWhitespaceBeforeSemicolonsFixer::class);

    $services->set(MultilineWhitespaceBeforeSemicolonsFixer::class)
        ->call('configure', [['strategy' => 'new_line_for_chained_calls']]);

    $services->set(SemicolonAfterInstructionFixer::class);

    $services->set(SpaceAfterSemicolonFixer::class);

    $services->set(DeclareStrictTypesFixer::class);

    $services->set(SingleQuoteFixer::class);

    $services->set(BlankLineBeforeStatementFixer::class);

    $services->set(IndentationTypeFixer::class);

    $services->set(LineEndingFixer::class);

    $services->set(MethodChainingIndentationFixer::class);

    $services->set(NoExtraBlankLinesFixer::class)
        ->call('configure', [['tokens' =>[
            'break',
            'case',
            'continue',
            'curly_brace_block',
            'default',
            'extra',
            'parenthesis_brace_block',
            'return',
            'square_brace_block',
            'switch',
            'throw',
            'use'
        ]]]);

    $services->set(NoSpacesAroundOffsetFixer::class);

    $services->set(NoSpacesInsideParenthesisFixer::class);

    $services->set(NoTrailingWhitespaceFixer::class);

    $services->set(NoWhitespaceInBlankLineFixer::class);

    $services->set(SingleBlankLineAtEofFixer::class);

    $services->set(ForbiddenAnnotationsSniff::class)
        ->property('forbiddenAnnotations', [
                '@api',
                '@author',
                '@category',
                '@copyright',
                '@created',
                '@license',
                '@package',
                '@since',
                '@subpackage',
                '@version'
            ]
        );

    $services->set(InlineDocCommentDeclarationSniff::class)
        ->property('allowDocCommentAboveReturn', true);

    $services->set(RequireOneLinePropertyDocCommentSniff::class);

    $parameters = $ecsConfig->parameters();

    $parameters->set('skip', [
        VisibilityRequiredFixer::class => ['*Spec.php'],
        DeclareStrictTypesFixer::class => ['*Spec.php'],
        ReturnTypeDeclarationFixer::class => ['*Spec.php'],
        VoidReturnFixer::class => ['*Spec.php'],
        MethodChainingIndentationFixer::class => ['Configuration.php']
    ]);
};
