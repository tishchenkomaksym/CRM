<?php
///**
// * Created by PhpStorm.
// * User: ivan.me
// * Date: 05.03.2019
// * Time: 12:03
// */
//
//namespace App\Form;
//
//use App\Entity\Sdt;
//use App\Entity\User;
//use App\Repository\UserRepository;
//use Symfony\Component\Form\PreloadedExtension;
//use Symfony\Component\Form\Test\TypeTestCase;
//
//class SdtTypeTest extends TypeTestCase
//{
//
//    protected $userRepository;
//
//    protected function setUp()
//    {
//        //https://symfony.com/doc/current/form/unit_testing.html
//        // mock any dependencies
//        $user=new User();
//        $user->setName('Person');
//        $this->userRepository = $this->createMock(UserRepository::class);
//        $this->userRepository->expects($this->any())
//                             ->method('findAll')
//                             ->willReturn([$user]);
//        parent::setUp();
//    }
//
//    protected function getExtensions()
//    {
//        // create a type instance with the mocked dependencies
//        $type = new SdtType($this->userRepository);
//
//        return [
//            // register the type instances with the PreloadedExtension
//            new PreloadedExtension([$type], []),
//        ];
//    }
//
//    /**
//     * @throws \Exception
//     */
//    public function testBuildForm()
//    {
//        $dateTime = new \DateTime();
//        $dateTime->setTime(0, 0);
//        $formData = [
//            'count' => '1',
//            'createDate' => $dateTime->format('Y-m-d'),
//            'acting' => 'Person',
//        ];
//
//        $objectToCompare = new Sdt();
//        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
//        $form = $this->factory->create(SdtType::class, $objectToCompare);
//
//        $object = new Sdt();
//        // ...populate $object properties with the data stored in $formData
//        $object->setActing($formData['acting']);
//        $object->setCount($formData['count']);
//        $object->setCreateDate($dateTime);
//        // submit the data to the form directly
//        $form->submit($formData);
//
//        $this->assertTrue($form->isSynchronized());
//
//        // check that $objectToCompare was modified as expected when the form was submitted
//        $this->assertEquals($object, $objectToCompare);
//
//        $view = $form->createView();
//        $children = $view->children;
//
//        foreach (array_keys($formData) as $key) {
//            $this->assertArrayHasKey($key, $children);
//        }
//    }
//}
